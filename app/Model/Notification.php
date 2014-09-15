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

        $dataSave = array();
        foreach ($listValue as $val) {
            if (!empty($data["folder_id"]) && $data["user_id"] != $val) {
                $dataSave[] = array(
                    "user_id" => $data["user_id"],
                    "user_notifcation_id" => $val,
                    "folder_id" => $data["folder_id"],
                    "shop_id" => @$data["shop_id"],
                    "type_messages" => $data["type_messages"],
                );
            }
        }
        if (!empty($dataSave)) {
            $this->create();
            if ($this->saveMany($dataSave))
                return false;
        }
        return true;
    }

}
