<?php

class CommentsController extends AppController {

    public $uses = array('Comment');

    /**
     * find comment
     */
    public function find() {
        $dataRequest = $this->request->data;
        $shop_id = @$dataRequest['shop_id'];
        if (!$shop_id) {
            return $this->responseNg('invalid params.');
        }
        $limit = 1;
        if (!empty($this->request->data['limit']))
            $limit = 0;
        $comments = $this->Comment->getListComment($shop_id, $limit);
        return $this->responseok($comments);
    }

    /**
     * save comment
     */
    function saveComment() {
        $dataRequest = $this->request->data;
        $shop_id = @$dataRequest['shop_id'];
        if (!$shop_id) {
            return $this->responseNg('invalid params.');
        }
        $dataSave = array(
            'user_id' => $this->user_id,
            "shop_id" => $shop_id,
            "message" => @$this->request->data["message"]
        );
        $this->Comment->create();
        if ($this->Comment->save($dataSave, false)) {
            $comments = $this->Comment->getListComment($shop_id);
            return $this->responseok($comments);
        } else {
            return $this->responseng('faild to share.');
        }
    }

}
