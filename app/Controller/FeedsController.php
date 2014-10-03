<?php

class FeedsController extends AppController {

    public $uses = array('UserFriend', 'FolderShop', 'Shop', 'FolderUser', 'User', 'Notification', 'NotificationSystem');

    /**
     * find feed or notification real time
     */
    function find() {
        
        $this->Notification->contain("User", "FolderUser", 'UserNotification', 'Shop');
        $data = $this->Notification->find("all", array(
            "conditions" => array("Notification.user_notifcation_id" => $this->user_id),
            "order" => array("Notification.modified DESC"),
        ));
        $type_Notification = Configure::read('NOTIFICATION');
        $result = array();
        foreach ($data as $value) {
            $folder = $this->FolderUser->findById($value["FolderUser"]["id"]);
            if (!$folder)
                continue;
            if ($value["Notification"]["type_messages"] == CREATED)
                $created = date("Y-m-d H:i", strtotime($value["Notification"]["created"]));
            else
                $created = date("Y-m-d H:i", strtotime($value["Notification"]["modified"]));
            $shop = $this->FolderShop->find("first", array(
                "conditions" => array(
                    "FolderShop.folder_id" => $value["FolderUser"]["id"]
                ),
                "order" => "FolderShop.rank ASC"
            ));
            if (!empty($shop)) {
                $img = $this->Shop->findPhotoUrlsByShopId($shop["FolderShop"]["shop_id"], $value["FolderUser"]["user_id"], $shop["FolderShop"]["folder_id"]);
            }
            if (empty($img))
                $img = "";
            $type_message = @$type_Notification[$value["Notification"]["type_messages"]];
            if ($value["Notification"]["type_messages"] == ADD) {
                $message = "{$value["User"]["name"]}さんが\n{$value["Shop"]["name"]}を新規追加しました。{$created}　";
            } else {
                $message = "{$value["User"]["name"]}さんが\n{$created}に{$type_message}";
            }
            $result[] = array(
                "id" => $value["Notification"]["id"],
                "folder_id" => $value["FolderUser"]["id"],
                "type_folder" => $value["FolderUser"]["type_folder"],
                "folder_name" => $value["FolderUser"]["name"],
                "user_name" => $value["User"]["name"],
                "user_id" => $value["FolderUser"]["user_id"],
                "modified" => $value["FolderUser"]["modified"],
                "type_noti" => NOTI_USER,
                "photo_url" => $img,
                "message" => $message,
            );
        }
        $datSystems = $this->NotificationSystem->find("all", array(
            "order" => array("NotificationSystem.modified DESC")
        ));
        foreach ($datSystems as $value) {
            $result[] = array(
                "photo_url" => $value["NotificationSystem"]["photo_url"],
                "message" => $value["NotificationSystem"]["message"],
                "modified" => $value["NotificationSystem"]["modified"],
                "type_noti" => NOTI_SYSTEM,
            );
        }
        if (!empty($result)) {
            $sortArray = array();
            foreach ($result as $key => $row) {
                $sortArray[$key] = $row['modified'];
            }
            array_multisort($sortArray, SORT_DESC, $result);
        }
        return $this->responseOk($result);
    }

    /**
     * update read_flag notification
     */
    function updateNoti() {
        $folder_id = @$this->request->data["folder_id"];
        $data = $this->Notification->find("list", array(
            "conditions" => array(
                "Notification.folder_id" => $folder_id,
                "Notification.read" => NOT_READ_NOTI,
                "Notification.type_messages" => array(EDITED, ADD),
            ),
            "order" => array("Notification.modified DESC"),
            "fields" => array("Notification.shop_id", "Notification.shop_id")
        ));
        $result = array();
        foreach ($data as $val) {
            $result[] = $val;
        }
        $this->Notification->updateAll(
                array('Notification.read' => READ_NOTI), array('Notification.folder_id ' => $folder_id)
        );
        return $this->responseOk($result);
    }

}
