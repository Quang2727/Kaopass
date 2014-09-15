<?php

class FolderShop extends AppModel {

    /**
     * add shop to folder
     * 
     * @param int shop_id
     * @param int folder_id
     * * @param int status
     */
    public function addShop($shop_id, $folder_id, $status = 0) {
        if (!$shop_id || !$folder_id) {
            return false;
        }

        $shop_num = $this->find('count', array(
            'conditions' => array(
                'folder_id' => $folder_id,
            ),
        ));

        $params = array(
            'FolderShop' => array(
                'shop_id' => $shop_id,
                'folder_id' => $folder_id,
                'status' => intval($status),
                'rank' => $shop_num + 1,
            ),
        );

        $ret = $this->save($params);

        return $ret;
    }

    /**
     * delete folder
     * 
     */
    public function deleteFolder($user_id, $folder_id = null, $listFolderShop = null) {
        App::uses('Folder', 'Utility');
        $folder = new Folder();
        if (empty($folder_id)) {
            return false;
        }

        if (!empty($listFolderShop)) {
            foreach ($listFolderShop as $shop_id) {
                $realPath = WWW_ROOT . 'shops' . DS . $user_id . DS . $folder_id;
                $realPath = $realPath . DS . $shop_id . DS;
                $folder->delete($realPath);
            }
        } else {
            $realPath = WWW_ROOT . 'shops' . DS . $user_id . DS . $folder_id;
            $folder->delete($realPath);
        }
    }

    /**
     * find shop order rank
     * 
     */
    public function findSortedShops($folder_id, $myfolder = null) {
        if (!$folder_id) {
            return false;
        }
        $conditions ['FolderShop.folder_id'] = $folder_id;
        $params = array(
            'joins' => array(
                array(
                    'table' => 'folders',
                    'alias' => 'FolderUser',
                    'type' => 'INNER',
                    'conditions' => array(
                        'FolderUser.id = FolderShop.folder_id'
                    )
                )
            ),
            'fields' => array(
                'FolderShop.folder_id',
                'FolderShop.shop_id',
                'FolderShop.id',
                'FolderShop.rank',
                'FolderShop.status',
                'FolderUser.*',
            ),
            'conditions' => $conditions,
            'order' => array(
                'FolderShop.rank ASC',
            ),
        );
        $result = $this->find('all', $params);
        if (is_array($result)) {
            return $result;
        }
        return array();
    }

    /**
     * update  shop in folder
     * 
     */
    public function updateFolder($folder_id, $shop_ids) {
        if (!$shop_ids) {
            return false;
        }

        $result = $this->deleteAll(array('folder_id' => $folder_id));
        if (!$result) {
            return false;
        }

        $shops = array();
        $rank = 0;
        foreach ($shop_ids as $id) {
            $rank += 1;
            $shops[] = array(
                'FolderShop' => array(
                    'folder_id' => $folder_id,
                    'shop_id' => $id,
                    'rank' => $rank,
                ),
            );
        }
        if (empty($shop_ids))
            return true;
        $ret = $this->saveAll($shops);
        return $ret;
    }

}
