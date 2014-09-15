<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

    public $components = array('RequestHandler');
    public $uses = array('User');
    // is_apiがfalseの場合, HTMLを返す. FYI) FacebookController
    public $is_api = true;
    public $user_id;

    public function beforeFilter() {
        if (strtolower($this->params["controller"]) == "kaopass")
            return;
        if ($this->is_api) {
            if (!$this->request->is('post')) {
                //   return $this->responseNg( 'request must be post method.' );
            }
            $this->checkAccessToken();
        }
    }

    public function beforeRender() {
        parent::beforeRender();
        $this->set('_serialize', array(
            'status',
            'message',
            'request',
            'response',
        ));
    }

    //
    // public methods
    //
    public function responseOk($res = array()) {
        return $this->setMessage('ok', 'success', $res);
    }

    public function responseNg($msg = '') {
        return $this->setMessage('ng', $msg, array());
    }

    //
    // private methods
    //
    private function setMessage($status, $msg, $res) {
        $this->set('status', $status);
        $this->set('message', $msg);
        $this->set('response', $res);
        $this->set('request', $this->request->data);

        $this->render();
    }

    private function checkAccessToken() {
      //   $this->request->data['api_token'] = "017d6a18596ec81a472d234457534a8c9e693532";
        if (@$this->request->data['api_token']) {
            $this->user_id = $this->User->
                    getUserIdByAPIToken($this->request->data['api_token']);
        }
        if (!$this->user_id) {
            return $this->responseNg('invalid api token .');
        }
    }

    function getIPadress() {
        $client_ip = '';
        //get IP client
        if (getenv("HTTP_CLIENT_IP")) {
            $client_ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR")) {
            $client_ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR")) {
            $client_ip = getenv("REMOTE_ADDR");
        }
        return $client_ip;
    }

}
