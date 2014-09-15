<?php

App::uses('FolderShop', 'Model');

class FolderUser extends AppModel {

    public $useTable = 'folders';

    /**
     * add shop to folder
     * 
     */
    public function rankFolder($conditions, $user_id) {
        APP::import("Model", array("User"));
        $this->User = new User();
        $user = $this->User->findById($user_id);
        $folder = $this->find('list', array(
            'conditions' => $conditions,
            "group by" => "Folder.id",
            "order" => "FolderUser.modified DESC",
            'fields' => array(
                'FolderUser.id',
                'FolderUser.id',
            ),
        ));
        $rank_folder = array();
        if (!empty($folder)) {
            if (empty($user["User"]["rank_folder"])) {
                $rank_folder = $folder;
            } else {
                $list = explode(",", $user["User"]["rank_folder"]);
                $listRank = array();
                foreach ($list as $val) {
                    if (!empty($folder[$val]))
                        $listRank[] = $val;
                }


                $rank_folder = array_merge($listRank, $folder);
                $rank_folder = array_unique($rank_folder);
            }
            $user["User"]["rank_folder"] = implode(",", $rank_folder);
            $this->User->save($user);
        }
        return $rank_folder;
    }

    public function copyFolder($folder_id, $user_id, $shop_id) {
        if (!$folder_id || !$user_id) {
            return false;
        }

        $folder = $this->findById($folder_id);
        if (!$folder) {
            return false;
        }
        $folder['FolderUser']['id'] = NULL;
        $folder['FolderUser']['user_id'] = $user_id;
        $ret = $this->save($folder);
        if (!is_array($ret)) {
            return false;
        }
        $new_id = $ret['FolderUser']['id'];
        $FolderShop = new FolderShop();
        $new_shops = array();

        $shops = $FolderShop->findAllByFolderId($folder_id);
        foreach ($shops as $shop) {
            $shop['FolderShop']['id'] = NULL;
            $shop['FolderShop']['folder_id'] = $new_id;
            $new_shops[] = $shop;
        }

        $shop = array();
        if (!empty($shop_id)) {
            $shop['FolderShop']['id'] = NULL;
            $shop['FolderShop']['folder_id'] = $new_id;
            $shop['FolderShop']['shop_id'] = $shop_id;
            $new_shops[] = $shop;
        }
        if (!empty($new_shops)) {
            $ret = $FolderShop->saveAll($new_shops);
            if (is_array($ret)) {
                return false;
            }
        }
        return true;
    }

    /**
     * find folder from list user
     * 
     */
    public function findAllByUserIds($user_ids) {
        if (!is_array($user_ids))
            return false;

        $folders = $this->find('all', array(
            'fields' => array(
                'FolderUser.id',
            ),
            'conditions' => array(
                'FolderUser.user_id' => $user_ids,
            ),
        ));
        if (!$folders)
            return false;
        return $folders;
    }

}
