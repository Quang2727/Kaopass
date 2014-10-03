<?php

App::uses('AppModel', 'Model');

class SocialSignupConfirmMail extends AppModel {

    public function getSocialSignupConfirmMailUser($conditions) {

        $result = $this->find("first", array(
            'conditions' => $conditions,
        ));

    	return $result;
    }
}
