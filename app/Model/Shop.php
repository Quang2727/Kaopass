<?php

App::uses('HttpSocket', 'Network/Http');
App::uses('Check', 'Model');

class Shop extends AppModel {

    /**
     * find folder shop 
     * 
     */
    public function appendShopSummary($folder_shops) {
        if (!$folder_shops) {
            return array();
        }
        $result = array();
        foreach ($folder_shops as $shop) {
            $detail = $this->find('first', array(
                'fields' => array(
                    'id',
                    'google_search_id',
                    'name',
                ),
                'conditions' => array(
                    'id' => $shop['FolderShop']['shop_id'],
                ),
            ));
            if (!$detail) {
                continue;
            }
            $detail['Shop']["folder"] = $this->getCountFolder($shop['FolderShop']['shop_id']);
            $detail['Shop']["comment"] = $this->getComment($shop['FolderShop']['shop_id']);
            $detail['Shop']["id"] = $shop['FolderShop']['id'];
            $photo_url = $this->findPhotoUrlsByShopId($shop['FolderShop']['shop_id'], $shop['FolderUser']['user_id'], $shop['FolderShop']['folder_id']);
            $detail['Shop']["img"] = "";
            if (!empty($photo_url)) {
                $detail['Shop']["img"] = $photo_url;
            }
            $result[] = array_merge(
                    $shop['FolderShop'], $detail['Shop']
            );
        }
        return $result;
    }

    /**
     * get comment shop 
     * 
     */
    public function getComment($shop_id) {
        APP::import("Model", array("Comment"));
        $this->Comment = new Comment();
        $data = $this->Comment->find("count", array(
            "conditions" => array(
                "Comment.shop_id" => $shop_id
            )
        ));
        return $data;
    }

    /**
     * count shop in folder
     * 
     */
    public function getCountFolder($shop_id) {
        APP::import("Model", array("FolderShop"));
        $this->FolderShop = new FolderShop();
        $data = $this->FolderShop->find("count", array(
            "conditions" => array(
                "FolderShop.shop_id" => $shop_id
            )
        ));
        return $data;
    }

    /**
     * find shop from google
     * 
     */
    public function findShops($google_api_key = null, $search_info = null) {
        //https://maps.googleapis.com/maps/api/place/textsearch/json?query=food|cafe|bar|restauran&keyword=a&language=true&language=en&key=AIzaSyCYzP2P-OVN8ijl3bSytEsXBz_-587HvoY
        //  $json_search = $HttpSocket->get("https://maps.googleapis.com/maps/api/place/textsearch/json?query={$request_parameters["query"]}&keyword={$request_parameters["keyword"]}&sensor={$request_parameters["sensor"]}&language={$request_parameters["language"]}&key={$request_parameters["key"]}");
        if (!is_array($search_info))
            return 0;
        $language = "en";
        if ($search_info["language"] == JAPAN) {
            $language = "ja";
        }
        if ($search_info["location"] == NOT_LOCATION) {
            $request_parameters = array(
                //  'location' => $search_info['lat'] . ',' . $search_info['lng'],
                'key' => $google_api_key,
                //'keyword' => $search_info['keyword'],
                'sensor' => 'true',
                'radius' => '500000',
                'name' => 'cruise',
                'language' => $language,
            );
            if (empty($search_info['keyword'])) {
                //  unset($request_parameters['keyword']);
                $request_parameters['query'] = 'food|cafe|bar|restaurant';
            } else {
                $request_parameters['query'] = 'food|cafe|bar|restaurant in ' . $search_info['keyword'];
                //  unset($request_parameters['radius']);
                $request_parameters['radius'] = "500000";
            }
            $fields_string = "";
            foreach ($request_parameters as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?" . $fields_string;
            $HttpSocket = new HttpSocket();
            $json_search = $HttpSocket->get($url);
        } else {
            $request_parameters = array(
                'location' => $search_info['lat'] . ',' . $search_info['lng'],
                'keyword' => $search_info['keyword'],
                'types' => 'food|cafe|bar|restaurant',
                //  'name' => 'cruise',
                'radius' => '10000',
                'sensor' => 'true',
                'language' => $language,
                'key' => $google_api_key,
                    //     'rankby' => 'distance',
            );
            if (empty($search_info['keyword'])) {
                unset($request_parameters['keyword']);
            } else {
                $request_parameters['radius'] = "10000";
            }
            //  https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=-33.8670522,151.1957362&radius=10000&keyword=Rhythmboat&types=food|cafe|bar|restaurant&name=cruise&key=AIzaSyDXl6cnTxBchVIbvnfpbdLUO4t6MVCefuw            
            // https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=-33.8670522,151.1957362&radius=1000&keyword=Signorelli&types=food|cafe|bar|restaurant&language=ja&key=AIzaSyDXl6cnTxBchVIbvnfpbdLUO4t6MVCefuw&
            $fields_string = "";
            foreach ($request_parameters as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?" . $fields_string;
            $HttpSocket = new HttpSocket();
            $json_search = $HttpSocket->get($url);
        }
        $res = array();
        if ($shops_from_google = json_decode($json_search, true)) {
            foreach ($shops_from_google['results'] as $shop) {
                $img = "";
                if (!empty($search_info['getImage'])) {
                    $listImage = $this->getImageFromGoogle($shop['reference']);
                    if (!empty($listImage))
                        $img = $listImage[0];
                }
                $res[] = array(
                    'name' => $shop['name'],
                    'id' => $shop['id'],
                    'reference' => $shop['reference'],
                    'img' => $img,
                );
            }
            return $res;
        }
    }

    // find PhotoUrl By ShopId / it can find only in shops table;
    // for feeds
    public function findPhotoUrlsByShopId($shop_id = null, $user_id = null, $folder_id = null) {
        if (!$shop_id)
            return false;
        $shop = $this->findById($shop_id);
        if (!is_array($shop))
            return false;
        $photo_urls = array();

        if (!empty($folder_id) && !empty($user_id) && !empty($shop_id))
            $photo_urls = $this->searchImageByFolder($folder_id, $shop_id, $user_id);
        if (count($photo_urls) > 0)
            return $photo_urls[0];
        if (!empty($shop['Shop']['img'])) {
            $listPhoto = $this->getImageShop($shop['Shop']['img']);
            if (!empty($listPhoto))
                foreach ($listPhoto as $key => $val) {
                    if (!empty($val))
                        return $listPhoto[$key];
                }
        }
        return false;
    }

    /**
     * get image shop 
     * 
     */
    function getImageShop($imgs) {
        $google_api_key = Configure::Read('Google.ApiKey');
        $result = explode(",", $imgs);
        $data = array();
        foreach ($result as $val) {
            $data[] = preg_replace('/key=(.*?)&photoreference/', "key={$google_api_key}&photoreference", $val);
        }
        return $data;
    }

    /**
     * get image shop 
     * 
     */
    public function getImageFromGoogle($reference_api_id) {
        $google_api_key = Configure::Read('Google.ApiKey');
        $HttpSocket = new HttpSocket();
        $json_from_google = $HttpSocket->get('https://maps.googleapis.com/maps/api/place/details/json', array(
            'reference' => $reference_api_id,
            'sensor' => 'true',
            'key' => $google_api_key,
            'language' => 'ja',
        ));

        $shop_from_google = json_decode($json_from_google, true);
        $HttpSocketPhoto = new HttpSocket();
        $photo_urls = array();
        if (!empty($shop_from_google['result']['photos'])) {
            foreach ($shop_from_google['result']['photos'] as $photo) {
                $params = "key=$google_api_key&photoreference=" . $photo['photo_reference'] . "&sensor=true&maxwidth=70";
                $photo_urls[] = 'https://maps.googleapis.com/maps/api/place/photo?' . $params;
            }
        }
        return $photo_urls;
    }

    /**
     * get image shop in case user upload image in shop
     * 
     */
    public function findByShopInput($shop, $folder_id = null, $user_id = null) {
        $shop_info = $shop;
        if (!empty($folder_id)) {
            $listPhoto = $this->searchImageByFolder($folder_id, $shop_info["Shop"]["id"], $user_id);
            if (!empty($listPhoto))
                $shop_info['photo_urls'] = $listPhoto;
        }
        return $shop_info;
    }

    public function findByGoogleId($google_api_key = null, $google_shop_ref = null, $google_search_id = null, $folder_id = null, $user_id = null) {
        if (!$google_api_key || !$google_shop_ref || !$google_search_id)
            return 0;
        $shop_info = null;
        $shop_from_self = $this->findByGoogleSearchId($google_search_id);
        if (is_array($shop_from_self) && count($shop_from_self) == 1) {
            $shop_info = $shop_from_self;
        }

        if (!$shop_info) {
            $HttpSocket = new HttpSocket();
            $json_from_google = $HttpSocket->get('https://maps.googleapis.com/maps/api/place/details/json', array(
                'reference' => $google_shop_ref,
                'sensor' => 'true',
                'key' => $google_api_key,
                'language' => 'ja',
            ));
            $shop_from_google = json_decode($json_from_google, true);
            if ($shop_from_google) {
                $res = array(
                    'google_search_id' => $google_search_id,
                    'reference' => $google_shop_ref,
                    'name' => $shop_from_google['result']['name'],
                    'phone' => @$shop_from_google['result']['formatted_phone_number'],
                    'address' => $shop_from_google['result']['formatted_address'],
                    'lat' => $shop_from_google['result']['geometry']['location']['lat'],
                    'lng' => $shop_from_google['result']['geometry']['location']['lng'],
                );

                if ($shop_from_google['result']['website']) {
                    $res['url'] = $shop_from_google['result']['website'];
                } else {
                    $res['url'] = $shop_from_google['result']['url'];
                }
                $photo_urls = array();
                // Googleからの画像を探す
                $HttpSocketPhoto = new HttpSocket();

                if (!empty($shop_from_google['result']['photos'])) {
                    foreach ($shop_from_google['result']['photos'] as $photo) {
                        $params = "key=$google_api_key&photoreference=" . $photo['photo_reference'] . "&sensor=true&maxwidth=304";
                        $photo_urls[] = 'https://maps.googleapis.com/maps/api/place/photo?' . $params;
                    }
                }

                if (!empty($photo_urls)) {
                    $res['img'] = implode(",", $photo_urls);
                }
                $this->create();
                if ($result = $this->save($res)) {
                    $shop_info = $result;
                }
            }
        }

        $shop_info['photo_urls'] = array();
        if (!empty($shop_info['Shop']['img'])) {
            $listPhoto = $this->getImageShop($shop_info['Shop']['img']);
            if (!empty($listPhoto))
                $shop_info['photo_urls'] = $listPhoto;
        }
        if (!empty($folder_id) && !empty($user_id)) {
            $listPhoto = $this->searchImageByFolder($folder_id, $shop_info["Shop"]["id"], $user_id);
            if (!empty($listPhoto)) {
                $shop_info['photo_urls'] = array_merge($listPhoto, $shop_info['photo_urls']);
            }
        }
        return $shop_info;
    }

    /**
     * get image shop in case user upload image in shop
     * 
     */
    public function searchImageByFolder($folder_id = null, $shop_id = null, $user_id = null) {
        App::uses('Folder', 'Utility');
        $realPath = WWW_ROOT . 'shops' . DS . $user_id . DS . $folder_id . DS . $shop_id;
        $folder = new Folder($realPath);
        $files = $folder->find('.*\.jpg', true);
        $result = array();
        asort($files);
        foreach ($files as $val) {
            $result[] = Router::url('/', true) . 'app/shops/' . $user_id . "/" . $folder_id . "/" . $shop_id . "/" . $val;
        }
        return $result;
    }

    /**
     * create html 
     * 
     */
    public function makeShopDetailViewedJSON($shopinfo = array()) {
        $pictures = "";
        if (!empty($shopinfo['photo_urls'])) {
            foreach ($shopinfo['photo_urls'] as $photo_url) {
                $pictures .= <<< EOF
         <div style="display:block;margin-bottom:10px;"> <img src="{$photo_url}" style="width:304px" align="center"> <br> </div>
EOF;
            }
        }
        $btn_web = "iVBORw0KGgoAAAANSUhEUgAAAG0AAAAeCAYAAAAmTpA5AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NDkxMSwgMjAxMy8xMC8yOS0xMTo0NzoxNiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkY3REEyMERGNzlDNjExRTNCRDk2RUE3Nzc0QjZENjBGIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkY3REEyMEUwNzlDNjExRTNCRDk2RUE3Nzc0QjZENjBGIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RjdEQTIwREQ3OUM2MTFFM0JEOTZFQTc3NzRCNkQ2MEYiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RjdEQTIwREU3OUM2MTFFM0JEOTZFQTc3NzRCNkQ2MEYiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7O3TGQAAALe0lEQVR42uyaZ2xU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";

        $btn_map = "iVBORw0KGgoAAAANSUhEUgAAAI0AAAAeCAYAAAARrJ1IAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NDkxMSwgMjAxMy8xMC8yOS0xMTo0NzoxNiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkY3REEyMERCNzlDNjExRTNCRDk2RUE3Nzc0QjZENjBGIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkY3REEyMERDNzlDNjExRTNCRDk2RUE3Nzc0QjZENjBGIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RjdEQTIwRDk3OUM2MTFFM0JEOTZFQTc3NzRCNkQ2MEYiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RjdEQTIwREE3OUM2MTFFM0JEOTZFQTc3NzRCNkQ2MEYiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4/wodMAAAOCUlEQVR42uybCWyV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";

        $btn_tel = "iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NDkxMSwgMjAxMy8xMC8yOS0xMTo0NzoxNiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjI2NzQ4MEI0NzlDNzExRTNCRDk2RUE3Nzc0QjZENjBGIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjI2NzQ4MEI1NzlDNzExRTNCRDk2RUE3Nzc0QjZENjBGIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RjdEQTIwRTE3OUM2MTFFM0JEOTZFQTc3NzRCNkQ2MEYiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RjdEQTIwRTI3OUM2MTFFM0JEOTZFQTc3NzRCNkQ2MEYiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7JuTVoAAAGZ0lEQVR42pxXa0xTVxw/vff29rY8SktLQV6lKyLydE4nRD5tCWoMLtmSLTpj3CfjJ7P4Zc7ExMTsy+ISP5iYzC+SzGiiUYj7YJyKOMxUUIE6ngXKu9DyKi193e53LrcdILTgSf65t73nnN//+Tv/ozh37hyJMxQQBsJDVJFIRBUMBtlwOMzgnVEoFPQp4imyLCsqlcoQ3v2YG5BFhETW25iLA0oBlRB1KBQSRFHUqFQqo9lsLtCmpubhXScIgtLn8wUwZmfn5oYmxscH/IHAFMMwPo7jfFi7BAnKCmwKmKWAsCwZoKnbsrLKdu/efah4x46adIMhh+U49doF4VDINz097bC9f9/c1tr658TkZCc8sAAlPLICoUTA9L8kWJGq0WjMB2prT1RVVX2jEgRtbIYofqgplDFlZhZRqa6q+vrvlpbbTU1NN3xLSw4eCmDKItUv6nq2pqZmLWgyQNNMJtOeE8eP/1JeWXkIbhPWA1s1IpGYcDyvtlgsnxWYzRWDg4N9CwsLM8iBiGy1uBaYlS3VZmdnV/9w8uTljMzM7RIg3ZBByBWKZUk0ZAXS9PptCM++/v7+f+fm5qYAHo5azazIXhXimaLX60u/P3bsUppOlxOzEqAhn490XLtGbNevb14BrNenp5uPHT16SavVFofC4RS5QhRRYA5loUEpGL86cuRHg9FoWQnqGR4m9rt3yejTp6Tvzh3ibG3dHLAMjrgXHqmrOwMvGICTRL3LyNbycLGmorz8YPHOnV9Irlox+u/fJ8OPH5NtNCz41nPzJgn7/ZsHx5qy8vIDpSUlX4IHaEWoGNlagef5jJr9+7+NxUi2NhIOk9neXuIZGyNiMEjSS0rIdHs7GYAyWwGmBmL/75CoRkpEEnAwFOJBDJ9m5+SUfuCpQEASRqkkU21tRFdURBieJ7N2O1nrmUQjLz+/Mi83txy5xHMSO0GDQqv1cwXDsKvKBhszKhXhBAHZwJAlt1uyfO/58yS9tHRLoHRfhmWVVqt1r31g4AnlWg4Mk1pQUFC8nosoYEpeHhFDIfyMELXBQDL37SPK5OQtW0wHarsEZZUsuRrZDJLSZG002VBRsVxzLEsWHA4p7h871BpNJh4CBaanDMexrHqjyRm7dhGNyYRcUhB3VxcZa27+aGDQpwYJxjNrTqN1M5LX6Ug2SokmGQXvuX2bBObnl9lsi4Oux8EhgYmIHUgl7IlTCsR88CBRG41SCVF32+/dW7nbshKbUATHppeeo3RmGGftosfjGYuXkWq42lJXJ1nNopwGHjwgrs7OGIfPdncT7/h4QvDFxcVRPHw0viCT4AJS3JZIW/Phw1IZUSKhzPXuyhXifP2ajDY1keazZ0n71avLiReHWOx2uw11vEjVC8Ln/t6+vpcRUQxvqDGsZlHTZadOEZV2+Wj2Tk2Rfy5eJE4QS3JuLpl49Uri83WBsS/CGey3218CT3J1EFnmH3I43g47HO2JSAAFT8pPn5ZKSwovxxGXzUaMlZVSCHpu3SJ+EM16BgwNDLQODw93UjwpxrRBg7udz54//yOWLHF4N7O6muw6c4ZwarUUc9/kJFlyuSRwWm6Ohw/XprKk9rPm5puwehp4S7FGgAVdTkxMOLNMpgI0ANa4rIRvKWYzMYJY/AD0weWekRFiKCsjaYWFJL+2lvA0HCsOmzdtbQ1Pnj69DpacAbA32nPRtpT2RNP3GhouGzMyLKZo9xHH7Vqrley9cIHM9fWR+cFBkgpl6H+r+jKAjo2Ovm9obLyC2LopKPXyytYngg8Rr9frQYZ3F23fvkeTlKRLZDkdQno60VosRNDr/++95Np2Tk723qiv/wmtTwdiOy93nOIqYOkPNGWY5Orq7n6Xm5PzCVqgbGmTRApERbaSrrH397+or6//2eVyvcV5TztNr9xzfdBlipIb0JSBUNwdHR0twUBgKSsry6JUqTQb9lpR5pK/Y63zr0ePfod7fwVh9AB0XgaN9dZrgaPgIQqOsdjb2/sGTfoLUJtbUKs1akFIQg/Nx5SQBQp6xsfHu1rQTzc2Nv7WYbM9ADs5V7h3VUOviHN3il5hBDANvVWoobkR1ufrdDqzkuPScFbzIB3KfG73zMwgqmIQNOyi1xco/lFXmKjlAZlg6F2Ix2EyNzIyMoTxXFZMIeWGQhFGOUYAFsSdKnphC8e7tP0nwACyKw6eWP4uqwAAAABJRU5ErkJggg==";

        $logo_powered_by_google = "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";

        $html = <<< EOF
<!DOCTYPE html>
<html>

<head>
<meta charset="utf8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<style>
body#shop-detail{
width:304px;
background-color:#f3f3f3;
margin:0;
margin-left:8px;
margin-right:8px;
font-family:"Lucida Grande","Lucida Sans Unicode","Lucida Sans",Arial,'?????? Pro W3','Hiragino Kaku Gothic Pro',Osaka,'MS P????',sans-serif,".HiraKakuInterface-W1";
}

div.references{
width:304px;
max-width:304px;
margin-top:16px;
}

div.references-shadow{
background-color:white;
width:264px;
max-width:264px;
padding: 20px;
border: 1px solid #f0f0f0;
-moz-box-shadow: 0 3px 5px rgba(0, 0, 0, .3);
-webkit-box-shadow: 0 3px 5px rgba(0, 0, 0, .3);
box-shadow:0 3px 5px rgba(0, 0, 0, 0.3);
filter: progid:DXImageTransform.Microsoft.Shadow(color=#777777,direction=0,strength=3,enabled=true);
-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
}

</style>
</head>
<body id="shop-detail">
    <h2>{$shopinfo['Shop']['name']}</h2>
    {$pictures}
  

    <div class="references-shadow">
        <dl>
            <dt>電話番号</dt>
            <dd>{$shopinfo['Shop']['phone']}</dd>
            <dt>住所</dt>
            <dd>{$shopinfo['Shop']['address']}</dd>
            <dt>URL</dt>
            <dd style="word-break: break-all">{$shopinfo['Shop']['url']}</dd>
        </dl>
    </div>

    <div class="references" align="right">
        <img src="data:image/png;base64,{$logo_powered_by_google}" width="100">
    </div>
</body>

</html>
EOF;
        $shopinfo['Shop']['html'] = $html;
        return $shopinfo;
    }

}
