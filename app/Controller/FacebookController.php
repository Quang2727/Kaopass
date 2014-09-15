<?php
/********************************************************************
 * FacebookController                                               *
 * (c) 2013-2014 Hiroshi Chiyokawa, You Kobayashi                   *
 * 使ってない 
 *******************************************************************/

App::import('Vendor', 'facebook/src/facebook');

class FacebookController extends AppController
{
    public $uses = array(
        'User',
        'UserFriend',
    );

    public $facebook;

    public function beforeFilter()
    {
        // FacebookControllerはJSONではなくHTMLを返す
        $this->is_api = false;
        parent::beforeFilter();

        $app_id = Configure::read( 'Facebook.id' );
        $secret = Configure::read( 'Facebook.secret' );
        $this->facebook = new Facebook( array(
            'appId'  => $app_id,
            'secret' => $secret,
            'cookie' => true,
        ));
    }

    public function entry()
    {
        $this->connect();
        $facebook_id = $this->facebook->getUser();
        if ( !$facebook_id ) {
        }

        try {
            $friends = $this->facebook->api("{$facebook_id}/friends");
        } catch (FacebookApiException $e){
            // TODO: Facebook APIコール失敗時の挙動
            error_log($e);
            return;
        }
        
        // facebookidsでユーザーを探して、userfriendに登録する
        // $user_ids = $this->User->findAllByFacebookId( /* array of facebook ids */ );
        $user_id = $this->User->registerByFacebook( $facebook_id, $this->facebook->getAccessToken() );
        $ret     = $this->UserFriend->saveSocialFriends( $user_id, $friends['data'] );
        if ( $ret ) {
            $this->redirect( array( 'action' => 'done' ) );
        }
        
        // TODO: 異常系
    }

    public function done()
    {
        // Facebook認証処理が終わった状態
        // クライアント側でURLを監視して必要な処理を行う
        $this->render();
    }

    //
    // private methods
    //
    private function connect()
    {
        $facebook_id = $this->facebook->getUser();
        if( !$facebook_id ){
            $this->auth();
        }
    }

    private function auth()
    {
        $login_url = $this->facebook->getLoginUrl( array( 
            'scope' => 'email,publish_stream,user_birthday,'
                .'user_education_history,user_likes'
        ));
        $this->redirect($login_url);
    }
}
