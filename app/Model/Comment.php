<?php

/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class Comment extends Model {

     /**
     * get comment of shop
     * 
     */
    function getListComment($shop_id, $limit = null) {
        $comments = $this->find("all", array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = Comment.user_id'
                    )
                )
            ),
            'conditions' => array(
                'Comment.shop_id' => $shop_id,
            ),
            "order" => "Comment.id DESC",
            "fields" => array("Comment.*", "User.name", "User.avatar",)
        ));
        $countComment = count($comments);
        $result = array();
        foreach ($comments as $val) {
            if (empty($val["User"]["avatar"])) {
                $val["Comment"]["avatarUser"] = Router::url('/', true) . 'app/systems/background/default.png';
            } else {
                if (!filter_var($val["User"]["avatar"], FILTER_VALIDATE_URL)) {
                    $val["Comment"]["avatarUser"] = Router::url('/', true) . $val["User"]["avatar"];
                } else {
                    $val["Comment"]["avatarUser"] = $val["User"]["avatar"];
                }
            }
            $val["Comment"]["time"] = date("H:i", strtotime($val["Comment"]["created"]));
            $val["Comment"]["nameUser"] = $val["User"]["name"];
            unset($val["User"]);
            $result[] = $val;
            if (empty($limit) && count($result) >= 5)
                break;
        }
        $ret = array(
            "data" => $result,
            "count" => $countComment
        );
        return $ret;
    }

}
