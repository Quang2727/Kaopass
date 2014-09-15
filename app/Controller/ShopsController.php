<?php

/* * ******************************************************************
 * - ShopsController                                                *
 * (c) 2013-2014 Hiroshi Chiyokawa, You Kobayashi                   *
 * ***************************************************************** */

class ShopsController extends AppController {

    // #curl http://localhost/grand2/kaopass/http-api/webapp/shops/find.json -XPOST -d"keyword=???????" -d"lat=35.690921" -d"lng=139.700258" -d"api_token=ab7e11734587e8eda6ea4233407b926cb13cf7c5"
    // search shops by Google Places API
    public $uses = array('FolderShop', 'Shop', 'FolderUser');

    /**
     * add shop to folder
     * 
     */
    function addShopToFolder() {
        $name = @$this->request->data['name'];
        $shop_id = @$this->request->data['shop_id'];
        if (!$shop_id || !$name)
            return $this->responseng('faild to add shop.');
        $dataFolder = array(
            "user_id" => $this->user_id,
            "name" => $name,
            "type_folder" => intval(@$this->request->data['type_folder'])
        );
        $ret = $this->FolderUser->save($dataFolder);
        $folder_id_old = @$this->request->data["older_folder_id"];
        $datashopFolder = array(
            "folder_id" => $ret["FolderUser"]["id"],
            "shop_id" => $shop_id,
            "rank" => 1,
            "status" => NO_MY_FOLDER
        );
        $result = $this->FolderShop->save($datashopFolder);
        App::uses('Folder', 'Utility');
        $folder = new Folder();

        if (!empty($folder_id_old)) {
            $oldFolder = $this->FolderUser->findById($folder_id_old);
            $newFolder = $ret;
            $path = WWW_ROOT . 'shops' . DS . $oldFolder["FolderUser"]["user_id"] . DS . $oldFolder["FolderUser"]["id"] . DS . $shop_id;
            $newPath = WWW_ROOT . 'shops' . DS . $newFolder["FolderUser"]["user_id"] . DS . $newFolder["FolderUser"]["id"] . DS . $result["FolderShop"]["shop_id"];
            if (is_dir($path)) {
                if (!is_dir($newPath))
                    $folder->create($newPath);
                $folder->copy(array(
                    'to' => $newPath,
                    'from' => $path,
                    'mode' => 0777,
                ));
            }
        }
        if ($result) {
            return $this->responseOk();
        }
        return $this->responseng('can not save data');
    }

    /**
     * find shop in folder
     * 
     */
    public function find() {
        $search_info = array(
            'keyword' => @$this->request->data['keyword'],
            'lat' => @$this->request->data['lat'],
            'lng' => @$this->request->data['lng'],
            'location' => @$this->request->data['location'],
            'language' => @$this->request->data['language'],
            "getImage" => @$this->request->data['getImage']
        );
        $api_key = Configure::Read('Google.ApiKey');
        $shops = $this->Shop->findShops($api_key, $search_info);
        if (!is_array($shops) || count($shops) == 0) {
            return $this->responseNg('Cannot search any shops.');
        }
        return $this->responseOk($shops);
    }

    /**
     * get image shop from google
     * 
     */
    function getImageShopByGoogle() {
        $reference = @$this->request->data['reference'];
        if (!$reference)
            return $this->responseOk("");
        $result = $this->Shop->getImageFromGoogle($reference);
        if (!empty($result))
            return $this->responseOk($result[0]);
        return $this->responseOk("");
    }

    /**
     * check exist shop of user
     * 
     */
    function checkExistShopByUser($shop_id) {
        APP::import("Model", array("FolderShop"));
        $this->FolderShop = new FolderShop();
        $params = array(
            'joins' => array(
                array(
                    'table' => 'folders',
                    'alias' => 'FolderUser',
                    'type' => 'INNER',
                    'conditions' => array(
                        'FolderUser.id = FolderShop.folder_id',
                        'FolderUser.user_id' => $this->user_id
                    )
                )
            ),
            'conditions' => array(
                'FolderShop.shop_id' => $shop_id,
            ),
        );
        $result = $this->FolderShop->find('first', $params);
        return $result;
    }

    public function getWithView() {
        APP::import("Model", array("FolderUser", "Comment"));
        $this->FolderUser = new FolderUser();
        $this->Comment = new Comment();
        $shop_id = $this->request->data['shop_id'];
        $folder_id = @$this->request->data['folder_id'];
        if (!$shop_id)
            return $this->responseNg('Invalid parameter.');
        $shop = $this->Shop->findById($shop_id);
        if (!$shop) {
            return $this->responseNg('Cannot find shop.');
        }
        $api_key = Configure::Read('Google.ApiKey');
        $user_id = $this->FolderUser->findById($folder_id);
        if (!empty($shop['Shop']['reference'])) {
            $shop_with_photo = $this->Shop->findByGoogleId($api_key, $shop['Shop']['reference'], $shop['Shop']['google_search_id'], $folder_id, @$user_id["FolderUser"]["user_id"]);
        } else {
            $shop_with_photo = $this->Shop->findByShopInput($shop, $folder_id, @$user_id["FolderUser"]["user_id"]);
        }

        $viewed_shop = $this->Shop->makeShopDetailViewedJSON($shop_with_photo);
        $btn_share = 1;
        if (!empty($this->request->data['folder_id'])) {
            if ($this->checkExistShopByUser($shop_id))
                $btn_share = 0;
        }
        $comments = $this->Comment->getListComment($shop_id);
        $linkMap = $linkWeb = $linkPhone = "";
        if (!empty($shop_with_photo["Shop"]["lat"]))
            $linkMap = "http://maps.google.com/maps?ll={$shop_with_photo['Shop']['lat']},{$shop_with_photo['Shop']['lng']}";
        if (!empty($shop_with_photo["Shop"]["url"]))
            $linkWeb = "{$shop_with_photo['Shop']['url']}";
        if (!empty($shop_with_photo["Shop"]["phone"])) {
            $linkPhone = "tel:{$shop_with_photo['Shop']['phone']}";
            $linkPhone = str_replace(" ", "", $linkPhone);
        }
        $result = array(
            "data" => $viewed_shop['Shop'],
            "shareData" => $btn_share,
            "comments" => $comments,
            "img" => @$shop_with_photo['photo_urls'][0],
            "linkMap" => $linkMap,
            "linkWeb" => $linkWeb,
            "linkPhone" => $linkPhone,
        );
        return $this->responseOk($result);
    }

    public function getPoweredByGoogle() {
        $google_shop_ref = $this->request->data['shop_ref'];
        $google_search_id = $this->request->data['shop_id'];
        if (!$google_shop_ref || !$google_search_id)
            return $this->responseNg('Invalid parameters.');

        $api_key = Configure::Read('Google.ApiKey');
        $shop = $this->Shop->findByGoogleId($api_key, $google_shop_ref, $google_search_id);
        if (!$shop) {
            return $this->responseNg('Cannot find shop.');
        }
        return $this->responseOk($shop['Shop']);
    }

    // #curl http://localhost/grand2/kaopass/http-api/webapp/shops/getPoweredByGoogle.json -XPOST -d"shop_ref=CrQBogAAAANKNEQBnrULODyAVQhrZzdZq0Dfo5DBQCV5108k7GpDHa9GaOzW2-_-QHIsf1Fl-gBzG-aaIdEtbphzneMg0vh1F5uElMCtjCQfv2ZZGTqxXXPgxgnh5GgX_fqdEXQ75G43Qe5sjTaVN3Wmk6pTNcYtxrJN1hNtM9XVGJGeOsaotuD4ewBcMSbOjLHsFM-X9SLlC8q8x70FRCYonoP1fK5ASbBaxYZSQdNavgsUXCkQEhDqKzBFn44H-29RGU5vCfRHGhROrGGhwdvu6WOC5K-ZY8s6NGbfzw" -d "shop_id=c24645b6860474d005979d17ae9e8dd9f49e4bd1" -d"api_token=ab7e11734587e8eda6ea4233407b926cb13cf7c5"
    // find shop info by Google Places API
    // ** this action's response is must be used with [Powered by Google] logo. **
    // ** this action's response is HTML included. **
    public function getWithViewPoweredByGoogle() {
        $google_shop_ref = $this->request->data['shop_ref'] . "";
        $google_search_id = $this->request->data['shop_id'] . "";
        if (!$google_shop_ref || !$google_search_id)
            return $this->responseNg('Invalid parameters.');
        $api_key = Configure::Read('Google.ApiKey');
        $shop = $this->Shop->findByGoogleId($api_key, $google_shop_ref, $google_search_id);

        if (!$shop) {
            return $this->responseNg('Cannot find shop.');
        }
        $viewed_shop = $this->Shop->makeShopDetailViewedJSON($shop);

        $linkMap = $linkWeb = $linkPhone = "";
        if (!empty($viewed_shop["Shop"]["lat"]))
            $linkMap = "http://maps.google.com/maps?ll={$viewed_shop['Shop']['lat']},{$viewed_shop['Shop']['lng']}";
        if (!empty($viewed_shop["Shop"]["url"]))
            $linkWeb = "{$viewed_shop['Shop']['url']}";
        if (!empty($viewed_shop["Shop"]["phone"])) {
            $linkPhone = "tel:{$viewed_shop['Shop']['phone']}";
            $linkPhone = str_replace(" ", "", $linkPhone);
        }

        $result = array(
            "data" => $viewed_shop['Shop'],
            "comments" => array(),
            "img" => @$shop['photo_urls'][0],
            "linkMap" => $linkMap,
            "linkWeb" => $linkWeb,
            "linkPhone" => $linkPhone,
        );
        return $this->responseOk($result);
    }

}
