<?php

class Notification extends AppModel {

    public $actsAs = array('Containable');
    public $belongsTo = array(
        'FolderUser' => array(
            'className' => 'FolderUser',
            'foreignKey' => 'folder_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'UserNotification' => array(
            'className' => 'User',
            'foreignKey' => 'user_notifcation_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Shop' => array(
            'className' => 'Shop',
            'foreignKey' => 'shop_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

    function saveNoti($data = null) {
        APP::import("Model", array("UserFriend", "FolderUser", "FolderShare"));
        $this->UserFriend = new UserFriend();
        $this->FolderUser = new FolderUser();
        $this->FolderShare = new FolderShare();
        if (empty($data))
            return false;
        $folder = $this->FolderUser->findById($data["folder_id"]);
        if (!$folder)
            return false;
        $type_folder = $folder["FolderUser"]["type_folder"];
        $listValue = array();
        if ($type_folder == FOLDER_SECRET) {
            $listValue = $this->FolderShare->find("list", array(
                'conditions' => array(
                    'FolderShare.folder_id' => $data["folder_id"],
                ),
                'fields' => array("FolderShare.user_id", "FolderShare.user_id"),
            ));
        } else {
            $listValue = $this->UserFriend->getListFriend($data["user_id"]);
        }
        $listValue[] = $folder["FolderUser"]["user_id"];
        $list_id = array();
        $list_message = array();
        $dataSave = array();
        $type_Notification = Configure::read('NOTIFICATION');
        foreach ($listValue as $val) {
            if (!empty($data["folder_id"]) && $data["user_id"] != $val) {
                $dataSave[] = array(
                    "user_id" => $data["user_id"],
                    "user_notifcation_id" => $val,
                    "folder_id" => $data["folder_id"],
                    "shop_id" => @$data["shop_id"],
                    "type_messages" => $data["type_messages"],
                );
                $type_message = @$type_Notification[$data["type_messages"]];
                $created = date("Y-m-d H:i");
                $result = $this->getInfoPush($data["user_id"], @$data["shop_id"]);
                if (!empty($result)) {
                    if ($data["type_messages"] == ADD) {
                        $message = "{$result["name_user"]}さんが{$result["name_shop"]}を新規追加しました。{$created}　";
                    } else {
                        $message = "{$result["name_user"]}さんが{$created}に{$type_message}";
                    }
                    $list_message[$val] = $message;
                    $list_id[] = $val;
                }
            }
        }

        if (!empty($dataSave)) {
            APP::import("Model", array("User"));
            $this->User = new User();
            $this->User->push_notification($list_id, "", $list_message);
            $this->create();
            if ($this->saveMany($dataSave))
                return false;
        }
        return true;
    }

    function getInfoPush($user_id = null, $shop_id = null) {
        $result = array();
        if (!empty($user_id)) {
            APP::import("Model", array("User", "Shop"));
            $this->User = new User();
            $this->Shop = new Shop();
            $user = $this->User->findById($user_id);
            $shop = $this->Shop->findById($shop_id);
            if (!empty($user)) {
                $result = array(
                    "name_user" => $user["User"]["name"],
                    "name_shop" => @$shop["Shop"]["name"],
                );
            }
        }
        return $result;
    }

}
