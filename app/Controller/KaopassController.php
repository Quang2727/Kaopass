<?php

App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');

class KaopassController extends AppController {

    public $uses = array('UserShare', 'User', 'FolderUser');

    const PREFIX = '-----';
    const APP_STORE = 'https://itunes.apple.com/jp/app/doragonpoka/id572233872?mt=8';
    const APP_KAOPASS = 'iOSKaopass://';

    function safe_b64encode($string) {
        return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($string));
    }

    function share($hasp = null) {
        $result = array(
            "data" => array(),
            "error" => 1,
        );
        if (empty($hasp)) {
            $user_id = @$this->request->data['user_id'];
            $folder_id = @$this->request->data['folder_id'];
            $this->user_id = $this->User->
                    getUserIdByAPIToken(@$this->request->data['api_token']);
        } else {
            $secret = explode(self::PREFIX, Security::cipher(base64_decode($hasp), Configure::read('Security.salt')));
            if (count($secret) < 4) {
                return $this->redirect(self::APP_STORE);
            }
            $this->user_id = $secret[0];
            $user_id = $secret[1];
            $folder_id = $secret[2];
        }
        if (empty($user_id) || empty($folder_id) || empty($this->user_id)) {
            if (empty($hasp))
                return $this->responseOk($result);
            else
                return $this->redirect(self::APP_STORE);
        } else {
            $my_user = $this->User->findById($this->user_id);
            $user = $this->User->findById($user_id);
            $folder = $this->FolderUser->find("first", array(
                "conditions" => array(
                    "FolderUser.id" => $folder_id,
                    "FolderUser.user_id" => $this->user_id,
                )
            ));
            if (empty($user) || empty($folder) || empty($my_user)) {
                if (empty($hasp))
                    return $this->responseOk($result);
                else
                    return $this->redirect(self::APP_STORE);
            }
        }
        if (empty($hasp)) {
            $str = $this->randomString();
            $hasp = $this->safe_b64encode(Security::cipher($this->user_id . self::PREFIX . $user_id . self::PREFIX . $folder_id . self::PREFIX . $str, Configure::read('Security.salt')));
            $link = Router::url('/', true) . "Kaopass/share/{$hasp}";
            $result["error"] = 0;
            $result["data"] = $link;
            return $this->responseOk($result);
        } else {
            APP::import("Model", array("FolderShare"));
            $folderShare = new FolderShare();

            $folderShareData = $folderShare->find("first", array(
                "conditions" => array("FolderShare.folder_id" => $folder_id, "FolderShare.user_id" => $user_id)
            ));
            if ($folderShareData) {
                return $this->redirect(self::APP_KAOPASS);
            }
            $dataSave = array(
                'user_id' => $user_id,
                "folder_id" => $folder_id
            );
            $folderShare->create();
            $folderShare->save($dataSave);
            return $this->redirect(self::APP_KAOPASS);
        }
    }

    function sendMail() {
//        $this->request->data['api_token'] = "017d6a18596ec81a472d234457534a8c9e693532";
//        $this->request->data['user_id'] = 3;
//        $this->request->data['folder_id'] = 216;
//        $this->request->data['title'] = "title";
//        $this->request->data['content'] = "content";
        App::uses('CakeEmail', 'Network/Email');
        $data = $this->request->data;
        $user_id = @$data['user_id'];
        $this->user_id = $this->User->
                getUserIdByAPIToken(@$data['api_token']);
        $folder_id = @$data['folder_id'];
        if (empty($user_id) || empty($folder_id) || empty($this->user_id)) {
            return $this->response->body(json_encode(array("error" => 0)));
        }
        $my_user = $this->User->findById($this->user_id);
        $folder = $this->FolderUser->findById($folder_id);
        $title = $my_user["User"]['name'] . "さんから" . $folder["FolderUser"]['name'] . "シークレットフォルダを共有しました";
        $user = $this->User->findById($user_id);
        $Email = new CakeEmail('kaopass');
        $Email->viewVars(array('data' => $data));
        $result = 1;
        try {
            $Email->template('sendMail')
                    ->emailFormat('html')
                    ->to($user["User"]["email"])
                    ->from(array($my_user["User"]['email'] => $my_user["User"]['name']))
                    ->subject($title)
                    ->send();
            $result = 0;
        } catch (Exception $e) {
            $result = 1;
        }
        $this->autoRender = false;
        $this->response->type("json");
        $this->response->body(json_encode(array("error" => $result)));
    }

    /**
     * convert url 
     * 
     */
    public function index($hasp = null) {
        if (empty($hasp)) {
            $this->user_id = $this->User->
                    getUserIdByAPIToken(@$this->request->data['api_token']);
            if (!empty($this->user_id)) {
                $str = $this->randomString();
                $authHash = $this->safe_b64encode(Security::cipher($this->user_id . self::PREFIX . $str, Configure::read('Security.salt')));
                $result = $this->getContentEmail($authHash);
                return $this->responseOk($result);
            } else {
                return $this->responseNg();
            }
        } else {
            $authLogin = explode(self::PREFIX, Security::cipher(base64_decode($hasp), Configure::read('Security.salt')));
            $client_ip = $this->getIPadress();
            if (count($authLogin) > 1) {
                $user_id = $authLogin[0];
                $ret = $this->User->find('first', array(
                    'conditions' => array(
                        'id' => $user_id
                    ),
                ));
                if (empty($ret))
                    return $this->redirect(self::APP_STORE);
                $user_share = $this->UserShare->find("first", array(
                    "conditions" => array(
                        "user_id" => $user_id,
                        "client_ip" => $client_ip,
                    )
                ));
                if (!empty($client_ip) && empty($user_share)) {
                    $this->UserShare->create();
                    $dataSave = array(
                        "user_id" => $user_id,
                        "client_ip" => $client_ip,
                    );
                    $this->UserShare->save($dataSave);
                }
            }
            return $this->redirect(self::APP_STORE);
        }
    }

    function getContentEmail($hash) {
        $url_banner = URL_BANNER;
        $link = Router::url('/', true) . "Kaopass/index/{$hash}";
        $html = "<a href={$link} target='blank'><img src={$url_banner} width='290' height='90' alt='モッピー！お金がたまるポイントサイト' /></a>";
        $html = " ＫＡＯＰＡＳＳ \n ■iOS版URLはこちら \n " . self::APP_STORE . " \n ■Android版URLはこちら \n https://play.google.com/store/apps/details?id=jp.co.asobism.drapoker";

        $data = array(
            "html" => $html,
            "link" => $link,
            "icon" => Router::url('/', true) . 'app/systems/icon/appicon_100.png',
        );
        return $data;
    }

    function randomString() {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        for ($i = 0; $i < 20; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $string;
    }

}
