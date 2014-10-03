<?php

class FoldersController extends AppController {

    public $uses = array('Shop', 'FolderUser', 'FolderShop', 'UserFriend', 'User', "FolderShare", "Notification");
    public $new_shop = array();

    /**
     * get list folder  of user 
     */
    function getUserInfo() {
        APP::import("Model", array("FolderShare"));
        $folderShare = new FolderShare();
        $user_id = @$this->request->data['user_id'];
        $user = $this->User->findById($user_id);
        if (!$user)
            return $this->responseNg("Can not find folder");

        $listFolder = $folderShare->find("list", array(
            'conditions' => array(
                'FolderShare.user_id' => $user_id,
            ),
            'fields' => array("FolderShare.folder_id", "FolderShare.folder_id"),
        ));
        $myShareFolder = $folderShare->find("list", array(
            'conditions' => array(
                'FolderShare.user_id' => $this->user_id,
            ),
            'fields' => array("FolderShare.folder_id", "FolderShare.folder_id"),
        ));
        if ($user_id == $this->user_id) {
            $conditions = array(
                "OR" => array(
                    array(
                        'FolderUser.user_id' => $user_id,
                        'FolderUser.type_folder' => array(FOLDER_NORMAL, FOLDER_SECRET),
                    ),
                    array(
                        'FolderUser.id' => $listFolder,
                        'FolderUser.type_folder' => array(FOLDER_NORMAL),
                    ),
                    array(
                        'FolderUser.id' => $myShareFolder,
                        'FolderUser.type_folder' => array(FOLDER_SECRET),
                    ),
            ));
        } else {
            $conditions = array(
                "OR" => array(
                    array(
                        'FolderUser.user_id' => $user_id,
                        'FolderUser.type_folder' => array(FOLDER_NORMAL),
                    ),
                    array(
                        'FolderUser.id' => $listFolder,
                        'FolderUser.type_folder' => array(FOLDER_NORMAL),
                    ),
                    array(
                        'FolderUser.id' => $myShareFolder,
                        'FolderUser.type_folder' => array(FOLDER_SECRET),
                    ),
            ));
        }

        $my_folder = $public_folder = $secret_folder = array();
        $rank_folder = $this->FolderUser->rankFolder($conditions, $user_id);
        if (!empty($rank_folder)) {
            $order = "FIELD(FolderUser.id," . implode(",", $rank_folder) . ")";
            $ret = $this->FolderUser->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'INNER',
                        'conditions' => array(
                            'User.id = FolderUser.user_id'
                        )
                    )
                ),
                'conditions' => $conditions,
                "group by" => "Folder.id",
                "order" => $order,
                'fields' => array(
                    'FolderUser.*',
                    'User.name',
                ),
            ));
            $ret = $this->getImageFolder($ret);
            foreach ($ret as $val) {
                if ($val["FolderUser"]["type_folder"] == FOLDER_SECRET) {
                    $secret_folder[] = $val;
                } else if ($val["FolderUser"]["type_folder"] == FOLDER_NORMAL) {
                    $my_folder[] = $val;
                } else {
                    $public_folder[] = $val;
                }
            }
        }
        $infoUser = $this->User->getInfoUser($user_id);
        $result = array(
            "folder" => $my_folder,
            "public" => $public_folder,
            "secret" => $secret_folder,
            "infoUser" => $infoUser,
        );
        return $this->responseOk($result);
    }

    function updateRankFolder() {
        $user_id = @$this->request->data['user_id'];
        $user = $this->User->findById($user_id);
        if (!$user)
            return $this->responseNg("Can not find folder");
        $user["User"]["rank_folder"] = $this->request->data['listRank'];
        $this->User->Save($user);
        return $this->responseOk();
    }

    /**
     *  check validate when user input shop 
     */
    function checkValidate() {
        $nameShop = $this->request->data['nameShop'];
        $phone = $this->request->data['phone'];
        $url = $this->request->data['url'];
        $address = $this->request->data['address'];
        $validate = "";
        if (empty($nameShop)) {
            $str = " 店舗名を入力してください。";
            if (!empty($validate))
                $validate.="\n" . $str;
            else {
                $validate.=$str;
            }
        }
        if (!empty($phone) && (!is_numeric($phone) || strlen($phone) < 10 || strlen($phone) > 11)) {
            $str = " 電話番後の形式ではありません。";
            if (!empty($validate))
                $validate.=" \n" . $str;
            else {
                $validate.=$str;
            }
        }
        if (!empty($url)) {
            if (strpos($url, 'http://') === false) {
                $url = "http://" . $url;
            }
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                $str = "  URLの形式では有りません。";
                if (!empty($validate))
                    $validate.="\n" . $str;
                else {
                    $validate.=$str;
                }
            }
        }
        return $this->responseOk($validate);
    }

    /**
     *  create folder
     */
    public function create() {
        $name = @$this->request->data['name'];
        if (!$name) {
            return $this->find();
        }
        $data = array(
            'name' => $name,
            'type_folder' => @$this->request->data['type_folder'],
            'user_id' => $this->user_id,
        );
        $ret = $this->FolderUser->save($data);
        if (!empty($ret)) {
            $dataNotfi = array(
                "user_id" => $this->user_id,
                "folder_id" => $ret["FolderUser"]["id"],
                "type_messages" => CREATED,
            );
            $this->Notification->saveNoti($dataNotfi);
            return $this->find();
        } else {
            return $this->find();
        }
        return $this->find();
    }

    /**
     *  share folder
     */
    function share() {
        APP::import("Model", array("FolderShare"));
        $folderShare = new FolderShare();
        $dataRequest = @$this->request->data;
        $folder_id = @$dataRequest['folder_id'];
        //  $this->log(@$this->request->data,"test-1");
        if (!$folder_id) {
            return $this->responseNg('invalid params.');
        }
        // Check user_id
        $folder = $this->FolderUser->findById($folder_id);
        if (empty($folder)) {
            return $this->responseNg('foler not exists');
        }
        if ($folder['FolderUser']['user_id'] == $this->user_id) {
            return $this->responseNg('you can\'t share your folder yourself.');
        }
        $folderShareData = $folderShare->find("first", array(
            "conditions" => array("FolderShare.folder_id" => $folder_id, "FolderShare.user_id" => $this->user_id)
        ));

        if ($folderShareData) {
            return $this->responseok("この店舗は既に追加されています。 "); // validate
        }

        $dataSave = array(
            'user_id' => $this->user_id,
            "folder_id" => $folder_id
        );
        $folderShare->create();
        if ($folderShare->save($dataSave)) {
            $list_id = array();
            $list_id[] = $folder['FolderUser']['user_id'];
            $this->User->push_notification($list_id, "フォルダをコピーされました");
            return $this->responseok();
        } else {
            return $this->responseng('faild to share.');
        }
    }

    /**
     *  share folder secret for user
     */
    function addFolderFriend() {
        APP::import("Model", array("FolderShare"));
        $folderShare = new FolderShare();
        $dataRequest = @$this->request->data;
        $folder_id = @$dataRequest['folder_id'];
        $user_id = @$dataRequest['user_id'];
        if (!$folder_id) {
            return $this->responseNg('invalid params.');
        }
        $folder = $this->FolderUser->find("first", array(
            "conditions" => array(
                "FolderUser.id" => $folder_id,
            )
        ));
        if (empty($folder)) {
            return $this->responseNg('foler not exists');
        }
        if ($folder['FolderUser']['user_id'] == $user_id) {
            return $this->responseok('you can\'t share your folder yourself.');
        }
        $folderShareData = $folderShare->find("first", array(
            "conditions" => array("FolderShare.folder_id" => $folder_id, "FolderShare.user_id" => $user_id)
        ));
        if ($folderShareData) {
            return $this->responseok("メールで友達を招待する"); // validate
        }
        $dataSave = array(
            'user_id' => $user_id,
            "folder_id" => $folder_id
        );
        $folderShare->create();
        if ($folderShare->save($dataSave)) {
            return $this->responseok("");
        } else {
            return $this->responseng('faild to share.');
        }
    }

    /**
     *  find shop type public 
     */
    public function findShopPublic() {
        $folder_id = @$this->request->data['folder_id'];
        if (!$folder_id) {
            return $this->responseng('faild to find shop.');
        }
        $result = $this->FolderShop->findSortedShops($folder_id);
        $ret = array();
        if ($result) {
            $ret = $this->Shop->appendShopSummary($result);
        }
        return $this->responseOk($ret);
    }

    /**
     *  find shop in folder
     */
    public function get($return = false) {
        $type_folder = @$this->request->data['type_folder'];
        $myFolder = 0;
        $shareFolder = 0;
        $folder_id = @$this->request->data['folder_id'];
        $folder = $this->FolderUser->findById($folder_id);
        $emptyData = array(
            "data" => array(),
            "shareFolder" => $shareFolder,
            "myFolder" => $myFolder
        );
        if (!$folder) {
            if ($return)
                return $emptyData;
            return $this->responseOk($emptyData);
        }
        if ($folder["FolderUser"]["user_id"] == $this->user_id)
            $myFolder = $status_folder = 1;
        $emptyData = array(
            "data" => array(),
            "shareFolder" => $shareFolder,
            "myFolder" => $myFolder // 
        );
        $status_folder = 0;  // only get shop shared in folder
        if ($type_folder != FOLDER_NORMAL)  // get all shop in folder 
            $status_folder = 1;
        if (!empty($this->request->data['isFriendFolder']) && $type_folder != FOLDER_SECRET) {
            APP::import("Model", array("FolderShare"));
            $folderShare = new FolderShare();
            $folderShareData = $folderShare->find("first", array(
                "conditions" => array("FolderShare.folder_id" => $folder_id, "FolderShare.user_id" => $this->user_id)
            ));
            if (!empty($folderShareData))
                $shareFolder = 1;
        }
        $result = $this->FolderShop->findSortedShops($folder_id, $status_folder);
        if ($result) {
            $ret = $this->Shop->appendShopSummary($result);
            $resultData = array(
                "data" => $ret,
                "shareFolder" => $shareFolder,
                "myFolder" => $myFolder
            );
            if ($return)
                return $resultData;
            return $this->responseOk($resultData);
        }
        if ($return)
            return $emptyData;
        return $this->responseOk($emptyData);
    }

    /**
     *  change position shop in folder
     */
    public function moveShop() {
        $folder_id = @$this->request->data['folder_id'];
        if (!$folder_id) {
            return $this->responseNg('invalid params.');
        }
        $resultCopy = $this->copy(MOVE_SHOP);
        if (empty($resultCopy)) {
            return $this->responseng('faild to update.');
        }
        $this->request->data["folder_id"] = $resultCopy;
        $result = $this->get(true);
        $result["folder_id"] = $resultCopy;
        return $this->responseOk($result);
    }

    function changeFolder() {
        $folder_id = @$this->request->data['folder_id'];
        if (!$folder_id) {
            return $this->responseNg('invalid params.');
        }
        $resultCopy = $this->copy(CHANGE_FOLDER);
        if (empty($resultCopy)) {
            return $this->responseng('faild to update.');
        }
        $this->request->data["folder_id"] = $resultCopy;
        //  $result = $this->get(true);
        $result["folder_id"] = $resultCopy;
        return $this->responseOk($result);
    }

    function rename() {
//        $this->request->data['folder_id'] = 9;
//        $this->request->data['name'] = "test";
        $folder_id = @$this->request->data['folder_id'];
        if (!$folder_id || !isset($this->request->data['name'])) {
            return $this->responseNg('invalid params.');
        }
        $resultCopy = $this->copy(RENAME);
        if (empty($resultCopy)) {
            return $this->responseng('faild to update.');
        }
        $this->request->data["folder_id"] = $resultCopy;
        $result["folder_id"] = $resultCopy;
        return $this->responseOk($result);
    }

    /**
     *  find folder 
     */
    public function find($return = false) {
        APP::import("Model", array("FolderShare"));
        $user_id = @$this->request->data['user_id'];
        if (!$user_id) {
            $user_id = $this->user_id;
        }
        $folderShare = new FolderShare();
        $listFolder = $folderShare->find("list", array(
            'conditions' => array(
                'FolderShare.user_id' => $user_id,
            ),
            'fields' => array("FolderShare.folder_id", "FolderShare.folder_id"),
        ));
        $conditions = array(
            "OR" => array(
                array(
                    'FolderUser.user_id' => $user_id,
                    'FolderUser.type_folder' => array(FOLDER_NORMAL, FOLDER_SECRET),
                ),
                array(
                    'FolderUser.id' => $listFolder,
                    'FolderUser.type_folder' => array(FOLDER_NORMAL, FOLDER_SECRET)
        )));

        $rank_folder = $this->FolderUser->rankFolder($conditions, $user_id);
        $ret = $infoUser = $secret_folder = $public_folder = $my_folder = array();
        if (!empty($rank_folder)) {
            $order = "FIELD(FolderUser.id," . implode(",", $rank_folder) . ")";
            $ret = $this->FolderUser->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'INNER',
                        'conditions' => array(
                            'User.id = FolderUser.user_id'
                        )
                    )
                ),
                'conditions' => $conditions,
                "order" => $order,
                "group by" => "Folder.id",
                'fields' => array(
                    'FolderUser.*',
                    'User.name',
                ),
            ));
            $ret = $this->getImageFolder($ret);
            if (empty($this->request->data['getAll']) && empty($this->request->data['friend'])) {
                $ret = $this->getDeltailFolder($ret, $user_id);
            } else {

                foreach ($ret as $val) {
                    if ($val["FolderUser"]["type_folder"] == FOLDER_NORMAL) {
                        $my_folder[] = $val;
                    } else {
                        $secret_folder[] = $val;
                    }
                }
                $result = array(
                    "folder" => $my_folder,
                    "secret" => $secret_folder,
                    "infoUser" => $infoUser,
                );
                return $this->responseOk($result);
            }
        } else {
            $public_folder = $this->findPublic();
            $ret = array(
                "folder" => $my_folder,
                "public" => $public_folder,
                "secret" => $secret_folder,
                "infoUser" => $infoUser,
            );
        }
        if (is_array($ret) && count($ret) > 0) {
            if ($return)
                return $ret;
            return $this->responseOk($ret);
        } else {
            if ($return)
                return false;
            return $this->responseNg('no folders');
        }
    }

    public function findPublic() {
        $ret = $this->FolderUser->find('all', array(
            'conditions' => array(
                'FolderUser.type_folder' => FOLDER_PUBLIC,
            ),
            'order' => 'modified DESC',
            "group by" => "Folder.id",
            'fields' => array(
                'FolderUser.*',
            ),
        ));
        $result = array();
        $ret = $this->getImageFolder($ret);
        if (is_array($ret) && count($ret) > 0) {
            foreach ($ret as $val) {
                $value = $this->getCountFolder($val);
                $val["FolderUser"]["number"] = $value["number"];
                $val["FolderUser"]["shared"] = 0;
                $result[] = $val;
            }
        }
        return $result;
    }

    /**
     *  get image and infomation user  in folder 
     */
    function getDeltailFolder($data, $user_id) {
        $infoUser = array();
        if (@$this->request->data['getInfo'] == 1)
            $infoUser = $this->User->getInfoUser($user_id);
        $my_folder = array();
        $secret_folder = array();
        foreach ($data as $val) {
            $value = $this->getCountFolder($val);
            $val["FolderUser"]["number"] = $value["number"];
            $val["FolderUser"]["shared"] = $value["shared"];
            if ($val["FolderUser"]["type_folder"] == FOLDER_SECRET) {
                $secret_folder[] = $val;
            } else {
                $my_folder[] = $val;
            }
        }

        $public_folder = $this->findPublic();

        $result = array(
            "folder" => $my_folder,
            "public" => $public_folder,
            "secret" => $secret_folder,
            "infoUser" => $infoUser,
        );
        return $result;
    }

    /**
     * count shop in folder 
     */
    function getCountFolder($val) {
        $folder_id = $val["FolderUser"]["id"];
        $type_folder = $val["FolderUser"]["type_folder"];
        $number = $countshare = 0;
        $shop = $this->FolderShop->find("all", array(
            "conditions" => array(
                "FolderShop.folder_id" => $folder_id
            ),
            "order" => "FolderShop.rank ASC"
        ));
        $number = count($shop);
        if ($val["FolderUser"]["type_folder"] == FOLDER_NORMAL) {
            foreach ($shop as $value) {
                if ($val["FolderUser"]["type_folder"] == FOLDER_NORMAL && $value["FolderShop"]["status"] == NO_MY_FOLDER)
                    $countshare ++;
            }
            if ($val["FolderUser"]["user_id"] != $this->user_id) {
                $countshare = 0;
            }
        }
        return array(
            "number" => $number,
            "shared" => $countshare,
        );
    }

    /**
     * get image shop in folder 
     */
    function getImageFolder($data) {
        $result = array();
        foreach ($data as $val) {
            $shop = $this->FolderShop->find("first", array(
                "conditions" => array(
                    "FolderShop.folder_id" => $val["FolderUser"]["id"]
                ),
                "order" => "FolderShop.rank ASC"
            ));
            if (!empty($shop)) {
                $img = $this->Shop->findPhotoUrlsByShopId($shop["FolderShop"]["shop_id"], $val["FolderUser"]["user_id"], $shop["FolderShop"]["folder_id"]);
                if (!empty($img))
                    $val["img"] = $img;
                else
                    $val["img"] = "";
                $result[] = $val;
            }
            else {
                $result[] = $val;
            }
        }
        return $result;
    }

    /**
     * delete folder 
     */
    public function delete() {
        $folder_id = @$this->request->data['folder_id'];
        if (!$folder_id) {
            return $this->responseNg('invalid params.');
        }
        $folder = $this->FolderUser->findById($folder_id);
        if (!$folder) {
            return $this->responseNg('folder not found.');
        }
        APP::import("Model", array("FolderShare"));
        $folderShare = new FolderShare();
        $folderShareData = $folderShare->find("first", array(
            "conditions" => array("FolderShare.folder_id" => $folder_id, "FolderShare.user_id" => $this->user_id)
        ));
        if (!empty($folderShareData["FolderShare"]["id"])) {
            $ret = $folderShare->delete($folderShareData["FolderShare"]["id"]);
        } else {
            $ret = $this->FolderUser->delete($folder_id);
            $this->FolderShop->deleteAll(array(
                'FolderShop.folder_id' => $folder_id
                    ), false);
        }
        if (is_array($ret)) {
            return $this->responseok();
        } else {
            return $this->responseng('faild to delete.');
        }
    }

    /**
     * update shop in folder
     * @param shops
     */
    public function update() {
        $folder_id = @$this->request->data['folder_id'];
        $shops = @$this->request->data['shops'];
        if (!$folder_id) {
            return $this->responseNg('invalid params.');
        }
        $resultCopy = $this->copy(UPDATE);
        if (!$resultCopy) {
            return $this->responseng('faild to delete.');
        }

        $this->request->data["folder_id"] = $resultCopy;
        $result = $this->get(true);
        $result["folder_id"] = $resultCopy;
        return $this->responseOk($result);
    }

    /**
     * add shop in folder
     * 
     */
    public function add_shop() {
        $shop_id = @$this->request->data['shop_id'];
        $folder_id = @$this->request->data['folder_id'];
        if (!$shop_id || !$folder_id) {
            return $this->responseOk(array(
                        "errors" => "この店舗は既に追加されています ",
                        "folder_id" => $folder_id,
            ));
        }
        $resultCopy = $this->copy(ADDSHOP);
        if (!$resultCopy) {
            if (!empty($this->request->data["message_errors"])) {
                return $this->responseOk(array(
                            "errors" => $this->request->data["message_errors"],
                            "folder_id" => $resultCopy,
                ));
            }
        }
        return $this->responseOk(array(
                    "errors" => "",
                    "folder_id" => $resultCopy,
        ));
    }

    /**
     * cut shop in folder
     * 
     */
    public function cut_shop() {
        $shop_id = @$this->request->data['shop_id'];
        $folder_id = @$this->request->data['folder_id'];
        if (!$shop_id || !$folder_id || !@$this->request->data['older_folder_id']) {
            return $this->responseOk("この店舗は既に追加されています ");
        }
        $resultCopy = $this->copy(CUT_SHOP);
        if (!$resultCopy) {
            if (!empty($this->request->data["message_errors"])) {
                return $this->responseOk(array(
                            "errors" => $this->request->data["message_errors"],
                            "folder_id" => $resultCopy,
                ));
            }
        }
        return $this->responseOk(array(
                    "errors" => "",
                    "folder_id" => $resultCopy,
        ));
    }

    /**
     * add shop in case user input 
     * 
     */
    public function add_shop_input() {
        $folder_id = @$this->request->data['folder_id'];
        if (!$folder_id) {
            return $this->responseNg('invalid params.');
        }
        $rest = $this->copy(ADD_INPUT_SHOP);
        if (!$rest) {
            return array("shop_id" => NULL, "folder_id" => NULL);
        }
        return $this->responseOk($rest);
    }

    public function copyFoler($oldFolder = NULL, $newFoler = NULL, $listShop = NULL) {
        App::uses('Folder', 'Utility');
        $folder = new Folder();
        if (empty($oldFolder) || empty($newFoler))
            return false;
        $this->FolderShop->create();
        if (!$this->FolderShop->saveAll($listShop))
            return false;
        foreach ($listShop as $val) {
            if (!empty($val['FolderShop']['shop_id'])) {
                $path = WWW_ROOT . 'shops' . DS . $oldFolder["FolderUser"]["user_id"] . DS . $oldFolder["FolderUser"]["id"] . DS . $val['FolderShop']['shop_id'];
                $newPath = WWW_ROOT . 'shops' . DS . $newFoler["FolderUser"]["user_id"] . DS . $newFoler["FolderUser"]["id"] . DS . $val['FolderShop']['shop_id'];
                if (is_dir($path)) {
                    if (!is_dir($newPath))
                        $folder->create($newPath);
                    $folder->copy(array(
                        'to' => $newPath,
                        'from' => $path, // will cause a cd() to occur
                        'mode' => 0777,
                    ));
                }
            }
        }
        return true;
    }

    /**
     * upload image to shop
     * 
     */
    public function uploadImage() {
        $result = $this->copy(UPLOAD);
        if (empty($result)) {
            return $this->responseng('faild to update.');
        }
        return $this->responseOk($result);
    }

    /**
     * upload image to shop
     * 
     */
    function uploadImageShop($folder_id = null, $shop_id = null) {
        App::uses('Folder', 'Utility');
        $folder = new Folder();
        $forlderData = $this->FolderUser->findById($folder_id);
        $user_id = $forlderData["FolderUser"]["user_id"];
        if (empty($user_id) || empty($folder_id) || empty($shop_id)) {
            return false;
        }
        $realPath = WWW_ROOT . 'shops' . DS . $user_id . DS . $folder_id . DS . $shop_id . DS;
        $folder->create($realPath);
        $date = new DateTime();
        $path_destination_file = $date->getTimestamp() . @$this->request->data['name'] . ".jpg";
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $realPath . $path_destination_file)) {
            return true;
        }
        return false;
    }

    /**
     * upload avatar user
     * 
     */
    function uploadAvatar() {
        App::uses('Folder', 'Utility');
        $folder = new Folder();
        if (empty($this->user_id)) {
            return $this->responseng('faild to upload.');
        }
        $ret = $this->User->find('first', array(
            'conditions' => array(
                'id' => $this->user_id,
            ),
        ));
        if (empty($ret)) {
            return $this->responseng('faild to upload.');
        }
        $keyUpload = @$this->request->data['keyUpload'];
        $realPath = WWW_ROOT . $keyUpload;
        $folder->create($realPath);
        $path_destination_file = $this->user_id . ".png";
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $realPath . DS . $path_destination_file)) {
            $ret["User"][$keyUpload] = 'app/' . $keyUpload . '/' . $path_destination_file;
            $this->User->save($ret["User"]);
            return $this->responseok();
        }
        return $this->responseng('faild to upload.');
    }

    /**
     * upload image in case user input
     * 
     */
    function uploadImageInput() {
        $folder_id = @$this->request->data['folder_id'];
        $shop_id = @$this->request->data['shop_id'];
        if (!$this->uploadImageShop($folder_id, $shop_id))
            return $this->responseng('faild to update.');
        return $this->responseOk($result);
    }

    /**
     * get shop in folder
     * 
     */
    function getFolderShopByFolder($folder_id) {
        $result = $this->FolderShop->find("all", array(
            "conditions" => array(
                "FolderShop.folder_id" => $folder_id,
            ),
            "order" => "FolderShop.rank ASC"
        ));
        return $result;
    }

    /**
     * check exist folder
     * 
     */
    public function checkMyFolder($folder_id, $shop_id) {
        if (!empty($folder_id) && !empty($shop_id)) {
            $result = $this->FolderShop->find("first", array(
                "conditions" => array(
                    "FolderShop.folder_id" => $folder_id,
                    "FolderShop.shop_id" => $shop_id,
                    "FolderShop.status" => NO_MY_FOLDER,
                ),
            ));
            if (!empty($result))
                return NO_MY_FOLDER;
            else {
                $result = $this->FolderUser->find("first", array(
                    "conditions" => array(
                        "FolderUser.id" => $folder_id,
                        "FolderUser.user_id" => $this->user_id,
                    )
                ));
                if (!empty($result))
                    return MY_FOLDER;
                return NO_MY_FOLDER;
            }
        }
        return MY_FOLDER;
    }

    /**
     * add shop to my folder
     * 
     */
    function addMyShop($folder_id = null, $folderData = null) {
        $shop_id = $this->request->data['shop_id'];
        $folder_id_old = @$this->request->data["older_folder_id"];
        $status = $this->checkMyFolder($folder_id_old, $shop_id);
        $ret = $this->FolderShop->addShop($shop_id, $folder_id, $status);
        $dataNotfi = array(
            "user_id" => $this->user_id,
            "folder_id" => $folder_id,
            "shop_id" => $shop_id,
            "type_messages" => ADD,
        );
        $this->Notification->saveNoti($dataNotfi);

        if (!$ret) {
            return FALSE;
        }
        App::uses('Folder', 'Utility');
        $folder = new Folder();

        if (!empty($folder_id_old)) {
            $oldFolder = $this->FolderUser->findById($folder_id_old);
            $newFolder = $this->FolderUser->findById($ret["FolderShop"]["folder_id"]);
            $path = WWW_ROOT . 'shops' . DS . $oldFolder["FolderUser"]["user_id"] . DS . $oldFolder["FolderUser"]["id"] . DS . $shop_id;
            $newPath = WWW_ROOT . 'shops' . DS . $newFolder["FolderUser"]["user_id"] . DS . $newFolder["FolderUser"]["id"] . DS . $shop_id;
            if (is_dir($path)) {
                if (!is_dir($newPath))
                    $folder->create($newPath);
                $folder->copy(array(
                    'to' => $newPath,
                    'from' => $path, // will cause a cd() to occur
                    'mode' => 0777,
                ));
            }
        }
        return $folder_id;
    }

    /**
     * cut shop to my folder
     * 
     */
    function cutMyShop($folder_id = null, $folderData = null) {
        $shop_id = $this->request->data['shop_id'];
        $folder_id_old = @$this->request->data["older_folder_id"];
        $params = array(
            'conditions' => array(
                'FolderShop.shop_id' => $shop_id,
                'FolderShop.folder_id' => $folder_id_old,
            ),
        );
        $folder_shop = $this->FolderShop->find('first', $params);
        $status = $this->checkMyFolder($folder_id_old, $shop_id);
        if (!empty($folder_shop)) {
            $this->FolderShop->delete($folder_shop["FolderShop"]["id"]);
        }
        $ret = $this->FolderShop->addShop($shop_id, $folder_id, $status);
        $dataNotfi = array(
            "user_id" => $this->user_id,
            "folder_id" => $folder_id,
            "shop_id" => $shop_id,
            "type_messages" => ADD,
        );
        $this->Notification->saveNoti($dataNotfi);
        if (!$ret) {
            return FALSE;
        }
        App::uses('Folder', 'Utility');
        $folder = new Folder();
        if (!empty($folder_id_old)) {
            $oldFolder = $this->FolderUser->findById($folder_id_old);
            $newFolder = $this->FolderUser->findById($ret["FolderShop"]["folder_id"]);
            $path = WWW_ROOT . 'shops' . DS . $oldFolder["FolderUser"]["user_id"] . DS . $oldFolder["FolderUser"]["id"] . DS . $shop_id;
            $newPath = WWW_ROOT . 'shops' . DS . $newFolder["FolderUser"]["user_id"] . DS . $newFolder["FolderUser"]["id"] . DS . $shop_id;
            if (is_dir($path)) {
                if (!is_dir($newPath))
                    $folder->create($newPath);
                $folder->copy(array(
                    'to' => $newPath,
                    'from' => $path, // will cause a cd() to occur
                    'mode' => 0777,
                ));
            }
        }
        return $folder_id;
    }

    /**
     * add shop to my folder in case user input
     * 
     */
    function inputMyShop($folder_id = null, $folderData = null) {
        $dataShop = array(
            'name' => @$this->request->data['name'],
            'phone' => @$this->request->data['phone'],
            'address' => @$this->request->data['address'],
            'url' => @$this->request->data['url'],
            'lat' => @$this->request->data['lat'],
            'lng' => @$this->request->data['lng'],
        );
        $ret = $this->Shop->save($dataShop);
        if (!$ret)
            return false;
        $shop_id = $ret["Shop"]["id"];
        $ret = $this->FolderShop->addShop($shop_id, $folder_id, MY_FOLDER);
        $dataNotfi = array(
            "user_id" => $this->user_id,
            "folder_id" => $folder_id,
            "shop_id" => $shop_id,
            "type_messages" => ADD,
        );
        $this->Notification->saveNoti($dataNotfi);

        if (!$ret) {
            return array("shop_id" => NULL, "folder_id" => NULL);
        } else
            return array("shop_id" => $shop_id, "folder_id" => $folder_id);
    }

    /**
     * update shop to my folder
     * 
     */
    function updateMyShop($folder_id = null, $folderData = null) {
        // Check user_id
        $shops = @$this->request->data['shops'];
        $folder = $this->FolderUser->findById($folder_id);
        $result = true;
        if (empty($shops)) {
            if (!empty($folder_id)) {
                $result = $this->FolderShop->deleteAll(array('folder_id' => $folder_id));
                if (!$result) {
                    // TODO: Error handling
                    return $false;
                }
                $this->FolderShop->deleteFolder($folderData["FolderUser"]["user_id"], $folder_id);
            }
        } else {
            $shops_arr = explode(',', $shops);
            $shops_arr[] = -1;
            $listFolderShop = $this->FolderShop->find("list", array(
                "conditions" => array(
                    "FolderShop.folder_id " => $folder_id,
                    "FolderShop.shop_id <>" => $shops_arr,
                ),
                "fields" => array("FolderShop.shop_id", "FolderShop.shop_id")
            ));
            if (!empty($listFolderShop)) {
                $result = $this->FolderShop->deleteAll(array('FolderShop.shop_id' => $listFolderShop, 'FolderShop.folder_id' => $folder_id));
                $this->FolderShop->deleteFolder($folderData["FolderUser"]["user_id"], $folder_id, $listFolderShop);
            }
        }
        $dataNotfi = array(
            "user_id" => $this->user_id,
            "folder_id" => $folder_id,
            "type_messages" => DELETED,
        );
        $this->Notification->saveNoti($dataNotfi);
        if (!$result) {
            return false;
        }
        return $folder_id;
    }

    /**
     * upload image shop to my folder
     * 
     */
    function uploadMyshop($folder_id = null, $folderData = null) {
        $dataNotfi = array(
            "user_id" => $this->user_id,
            "folder_id" => $this->request->data['folder_id'],
            "shop_id" => $this->request->data['shop_id'],
            "type_messages" => EDITED,
        );
        $this->Notification->saveNoti($dataNotfi);
        if ($this->uploadImageShop($this->request->data['folder_id'], $this->request->data['shop_id']))
            return $this->request->data['folder_id'];
        return false;
    }

    /**
     * change type folder
     * 
     */
    function changeMyFolder($folder_id) {
        $folderData = $this->FolderUser->findById($folder_id);
        $folderData["FolderUser"]["type_folder"] = FOLDER_SECRET;
        $this->FolderUser->save($folderData);
        return $this->request->data['folder_id'];
    }

    /**
     * rename folder
     * 
     */
    function renameMyFolder($folder_id) {
        $folderData = $this->FolderUser->findById($folder_id);
        $folderData["FolderUser"]["name"] = $this->request->data['name'];
        $this->FolderUser->save($folderData);
        return $this->request->data['folder_id'];
    }

    /**
     * change position shop in my folder
     * 
     */
    function moveMyshop($folder_id = null, $folderData = null) {
        $folder_id = $this->request->data["folder_id"];
        $listId = explode(",", $this->request->data["listId"]);
        $listRank = explode(",", $this->request->data["listRank"]);
        $result = array();
        foreach ($listId as $key => $val) {
            $result[$listId[$key]] = $listRank[$key];
        }
        $list_shops = array();
        $shops = $this->FolderShop->findAllByFolderId($folder_id);
        foreach ($shops as $shop) {
            $shop['FolderShop']['folder_id'] = $folder_id;
            $shop['FolderShop']['rank'] = intval(@$result[$shop['FolderShop']['shop_id']]);
            $list_shops[] = $shop;
        }
        if ($list_shops) {
            $dataNotfi = array(
                "user_id" => $this->user_id,
                "folder_id" => $folder_id,
                "type_messages" => MOVE,
            );
            $this->Notification->saveNoti($dataNotfi);
            if ($this->FolderShop->saveAll($list_shops))
                return $this->request->data['folder_id'];
            return false;
        }
        return $this->request->data['folder_id'];
    }

    /**
     * update shop in friend's folder
     * 
     */
    function updateFriendshop($saveFolder = null, $shops_arr) {

        if (!empty($shops_arr)) {
            $rank = 0;
            foreach ($shops_arr as $id) {
                $rank += 1;
                $this->new_shop[] = array(
                    'FolderShop' => array(
                        'folder_id' => $saveFolder["FolderUser"]["id"],
                        'shop_id' => $id,
                        'status' => NO_MY_FOLDER,
                        'rank' => $rank,
                    ),
                );
            }
        }
        return NULL;
    }

    /**
     * add shop in friend's folder
     * 
     */
    function addFriendshop($folder_id, $saveFolder = null) {
        $shops = $this->getFolderShopByFolder($folder_id);
        $rank = 0;
        foreach ($shops as $shop) {
            $rank ++;
            unset($shop['FolderShop']['created']);
            unset($shop['FolderShop']['modified']);
            $shop['FolderShop']['id'] = NULL;
            $shop['FolderShop']['folder_id'] = $saveFolder["FolderUser"]["id"];
            $shop['FolderShop']['rank'] = $rank;
            $shop['FolderShop']['status'] = NO_MY_FOLDER;
            $this->new_shop[] = $shop;
        }
        $shop_id = @$this->request->data['shop_id'];
        if (!empty($shop_id)) {
            $folder_id_old = @$this->request->data["older_folder_id"];
            $status = $this->checkMyFolder($folder_id_old, $shop_id);
            $params = array(
                'FolderShop' => array(
                    'id' => NULL,
                    'folder_id' => $saveFolder["FolderUser"]["id"],
                    'shop_id' => $shop_id,
                    'status' => $status,
                    'rank' => $rank++,
                ),
            );
            $this->new_shop[] = $params;
        }
        return $shop_id;
    }

    /**
     * cut shop in friend's folder
     * 
     */
    function cutFriendshop($folder_id, $saveFolder = null) {
        $shop_id = @$this->request->data['shop_id'];
        $folder_id_old = @$this->request->data["older_folder_id"];
        $params = array(
            'conditions' => array(
                'FolderShop.shop_id' => $shop_id,
                'FolderShop.folder_id' => $folder_id_old,
            ),
        );
        $folder_shop = $this->FolderShop->find('first', $params);
        $status = $this->checkMyFolder($folder_id_old, $shop_id);
        if (!empty($folder_shop)) {
            $this->FolderShop->delete($folder_shop["FolderShop"]["id"]);
        }
        $shops = $this->getFolderShopByFolder($folder_id);
        $rank = 0;


        foreach ($shops as $shop) {
            $rank ++;
            unset($shop['FolderShop']['created']);
            unset($shop['FolderShop']['modified']);
            $shop['FolderShop']['id'] = NULL;
            $shop['FolderShop']['folder_id'] = $saveFolder["FolderUser"]["id"];
            $shop['FolderShop']['rank'] = $rank;
            $shop['FolderShop']['status'] = NO_MY_FOLDER;
            $this->new_shop[] = $shop;
        }
        if (!empty($shop_id)) {

            $params = array(
                'FolderShop' => array(
                    'id' => NULL,
                    'folder_id' => $saveFolder["FolderUser"]["id"],
                    'shop_id' => $shop_id,
                    'status' => $status,
                    'rank' => $rank++,
                ),
            );
            $this->new_shop[] = $params;
        }
        return $shop_id;
    }

    /**
     * add shop in friend's folder in case user input
     * 
     */
    function inputFriendshop($folder_id, $saveFolder = null) {
        $shops = $this->getFolderShopByFolder($folder_id);
        $rank = 0;
        foreach ($shops as $shop) {
            $rank ++;
            unset($shop['FolderShop']['created']);
            unset($shop['FolderShop']['modified']);
            $shop['FolderShop']['id'] = NULL;
            $shop['FolderShop']['folder_id'] = $saveFolder["FolderUser"]["id"];
            $shop['FolderShop']['rank'] = $rank;
            $shop['FolderShop']['status'] = NO_MY_FOLDER;
            $this->new_shop[] = $shop;
        }
        $dataShop = array(
            'name' => @$this->request->data['name'],
            'phone' => @$this->request->data['phone'],
            'address' => @$this->request->data['address'],
            'url' => @$this->request->data['url'],
            'lat' => @$this->request->data['lat'],
            'lng' => @$this->request->data['lng'],
        );
        $ret = $this->Shop->save($dataShop);
        if (!$ret)
            return false;
        $shop_id = $ret["Shop"]["id"];
        if (!empty($shop_id)) {
            $params = array(
                'FolderShop' => array(
                    'id' => NULL,
                    'folder_id' => $saveFolder["FolderUser"]["id"],
                    'shop_id' => $shop_id,
                    'rank' => $rank++,
                    'status' => MY_FOLDER,
                ),
            );
            $this->new_shop[] = $params;
        }
        return $shop_id;
    }

    /**
     * upload image shop in friend's folder
     * 
     */
    function uploadFriendshop($folder_id, $saveFolder = null) {
        $shops = $this->getFolderShopByFolder($folder_id);
        $rank = 0;
        foreach ($shops as $shop) {
            $rank ++;
            unset($shop['FolderShop']['created']);
            unset($shop['FolderShop']['modified']);
            $shop['FolderShop']['id'] = NULL;
            $shop['FolderShop']['folder_id'] = $saveFolder["FolderUser"]["id"];
            $shop['FolderShop']['rank'] = $rank;
            $shop['FolderShop']['status'] = NO_MY_FOLDER;
            $this->new_shop[] = $shop;
        }
        return NULL;
    }

    /**
     * change position shop in friend's folder
     * 
     */
    function changeFriendFolder($folder_id, $saveFolder) {
        $shops = $this->getFolderShopByFolder($folder_id);
        $rank = 0;
        foreach ($shops as $shop) {
            $rank ++;
            unset($shop['FolderShop']['created']);
            unset($shop['FolderShop']['modified']);
            $shop['FolderShop']['id'] = NULL;
            $shop['FolderShop']['folder_id'] = $saveFolder["FolderUser"]["id"];
            $shop['FolderShop']['rank'] = $rank;
            $shop['FolderShop']['status'] = NO_MY_FOLDER;
            $this->new_shop[] = $shop;
        }
        return NULL;
    }

    /**
     * change position shop in friend's folder
     * 
     */
    function renameFriendFolder($folder_id, $saveFolder) {
        $shops = $this->getFolderShopByFolder($folder_id);
        $rank = 0;
        foreach ($shops as $shop) {
            $rank ++;
            unset($shop['FolderShop']['created']);
            unset($shop['FolderShop']['modified']);
            $shop['FolderShop']['id'] = NULL;
            $shop['FolderShop']['folder_id'] = $saveFolder["FolderUser"]["id"];
            $shop['FolderShop']['rank'] = $rank;
            $shop['FolderShop']['status'] = NO_MY_FOLDER;
            $this->new_shop[] = $shop;
        }
        return NULL;
    }

    /**
     * change position shop in friend's folder
     * 
     */
    function moveFriendshop($folder_id, $saveFolder = null) {
        $listId = explode(",", $this->request->data["listId"]);
        $listRank = explode(",", $this->request->data["listRank"]);
        $resultList = array();
        foreach ($listId as $key => $val) {
            $resultList[$listId[$key]] = $listRank[$key];
        }
        $shops = $this->getFolderShopByFolder($folder_id);
        foreach ($shops as $shop) {
            unset($shop['FolderShop']['created']);
            unset($shop['FolderShop']['modified']);
            $shop['FolderShop']['folder_id'] = $saveFolder["FolderUser"]["id"];
            $shop['FolderShop']['rank'] = intval(@$resultList[$shop['FolderShop']['shop_id']]);
            $shop['FolderShop']['id'] = NULL;
            $shop['FolderShop']['status'] = NO_MY_FOLDER;
            $this->new_shop[] = $shop;
        }
        return NULL;
    }

    /**
     * function  add,copy,move,share .... shop in folder
     * 
     */
    public function copy($call = null) {
        APP::import("Model", array("FolderShare"));
        $folder_id = @$this->request->data['folder_id'];
        $this->new_shop = array();
        if (!$folder_id) {
            return $this->responseNg('invalid params.');
        }
        $folderData = $this->FolderUser->findById($folder_id);
        if (!empty($call)) {
            if ($call == ADDSHOP || $call == CUT_SHOP) {
                $params = array(
                    'conditions' => array(
                        'FolderShop.shop_id' => @$this->request->data['shop_id'],
                        'FolderShop.folder_id' => @$this->request->data['folder_id'],
                    ),
                );
                $result = $this->FolderShop->find('first', $params);
                if (!empty($result)) {
                    $this->request->data["message_errors"] = "この店舗は既に追加されています。";
                    return FALSE;
                }
            }
            APP::import("Model", array("FolderShare"));
            $folderShare = new FolderShare();
            $folderShareData = $folderShare->find("first", array(
                'joins' => array(
                    array(
                        'table' => 'folders',
                        'alias' => 'FolderUser',
                        'type' => 'INNER',
                        'conditions' => array(
                            'FolderUser.id = FolderShare.folder_id',
                            'FolderUser.type_folder' => FOLDER_NORMAL
                        )
                    )
                ),
                "conditions" => array("FolderShare.folder_id" => $folder_id,
                    "FolderShare.user_id" => $this->user_id
                )
            ));

            if (!empty($folderShareData["FolderShare"]["id"])) {
                $folderShare->delete($folderShareData["FolderShare"]["id"]);
                $buffer_Folder = $folder = $this->FolderUser->findById($folder_id);
                $oldFolder = array();
                if (!$folder) {
                    return false;
                }
                $folder['FolderUser']['id'] = NULL;
                $folder['FolderUser']['user_id'] = $this->user_id;
                if ($call == CHANGE_FOLDER)
                    $folder['FolderUser']['type_folder'] = FOLDER_SECRET;
                if ($call == RENAME)
                    $folder['FolderUser']['name'] = $this->request->data['name'];
                $saveFolder = $this->FolderUser->save($folder);
                if (!$saveFolder) {
                    return false;
                }
                $shop_id = NULL;
                switch ($call) {
                    case ADDSHOP:
                        $shop_id = $this->addFriendshop($folder_id, $saveFolder);
                        break;
                    case CUT_SHOP:
                        $shop_id = $this->cutFriendshop($folder_id, $saveFolder);
                        break;
                    case ADD_INPUT_SHOP:
                        $shop_id = $this->inputFriendshop($folder_id, $saveFolder);
                        break;
                    case UPDATE:
                        $shops = @$this->request->data['shops'];
                        if (!empty($shops)) {

                            $shops_arr = explode(',', $shops);

                            $shop_id = $this->updateFriendshop($saveFolder, $shops_arr);
                        } else {

                            return $saveFolder["FolderUser"]["id"];
                        }
                        break;
                    case UPLOAD:
                        $shop_id = $this->uploadFriendshop($folder_id, $saveFolder);
                        break;
                    case MOVE_SHOP:
                        $shop_id = $this->moveFriendshop($folder_id, $saveFolder);
                        break;
                    case CHANGE_FOLDER:
                        $shop_id = $this->changeFriendFolder($folder_id, $saveFolder);
                        break;
                    case RENAME:
                        $shop_id = $this->renameFriendFolder($folder_id, $saveFolder);
                        break;
                }
                if (!empty($this->new_shop)) {
                    return $this->saveShopFolder($saveFolder, $shop_id, $call, $buffer_Folder);
                }
            } else {

                switch ($call) {
                    case ADDSHOP:
                        return $this->addMyShop($folder_id, $folderData);
                    case CUT_SHOP:
                        return $this->cutMyShop($folder_id, $folderData);
                    case ADD_INPUT_SHOP:
                        return $this->inputMyShop($folder_id, $folderData);
                    case UPDATE:
                        return $this->updateMyShop($folder_id, $folderData);
                    case UPLOAD:
                        return $this->uploadMyshop($folder_id, $folderData);
                    case MOVE_SHOP:
                        return $this->moveMyshop($folder_id, $folderData);
                    case CHANGE_FOLDER:
                        return $this->changeMyFolder($folder_id);
                    case RENAME:
                        return $this->renameMyFolder($folder_id);
                }
                return true;
            }
            return true;
        }
    }

    /**
     * save shop in folder
     * 
     */
    function saveShopFolder($saveFolder, $shop_id, $call, $buffer_Folder) {
        $dataNotfi = array(
            "user_id" => $this->user_id,
            "folder_id" => $saveFolder["FolderUser"]["id"],
            "type_messages" => CREATED,
        );
        $this->Notification->saveNoti($dataNotfi);
        App::uses('Folder', 'Utility');
        $folder = new Folder();
        $folder_id_old = @$this->request->data["older_folder_id"];
        if (!empty($folder_id_old) && !empty($shop_id)) {
            $oldFolder = $this->FolderUser->findById($folder_id_old);
            $path = WWW_ROOT . 'shops' . DS . $oldFolder["FolderUser"]["user_id"] . DS . $oldFolder["FolderUser"]["id"] . DS . $shop_id;
            $newPath = WWW_ROOT . 'shops' . DS . $saveFolder["FolderUser"]["user_id"] . DS . $saveFolder["FolderUser"]["id"] . DS . $shop_id;
            if (is_dir($path)) {
                if (!is_dir($newPath))
                    $folder->create($newPath);
                $folder->copy(array(
                    'to' => $newPath,
                    'from' => $path, // will cause a cd() to occur
                    'mode' => 0777,
                ));
            }
        }
        if (!$this->copyFoler($buffer_Folder, $saveFolder, $this->new_shop))
            return false;
        if ($call == UPLOAD) {
            if ($this->uploadImageShop($saveFolder["FolderUser"]["id"], @$this->request->data['shop_id']))
                return $saveFolder["FolderUser"]["id"];
            return false;
        }
        else if ($call == MOVE_SHOP || $call == UPDATE || $call == ADDSHOP || $call == CUT_SHOP || $call == CHANGE_FOLDER || $call == RENAME) {
            return $saveFolder["FolderUser"]["id"];
        } else if ($call == ADD_INPUT_SHOP) {
            return array("shop_id" => $shop_id, "folder_id" => $saveFolder["FolderUser"]["id"]);
        }
        return true;
    }

}
