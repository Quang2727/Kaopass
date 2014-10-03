<?php
App::uses('AppModel', 'Model');
/**
 * MailDelivery Model
 *
 */
class MailDelivery extends AppModel {

    var $name = 'MailDelivery';

    var $validate = array(
        'user_id' => array(
            'numeric' => array(
                'rule' => array(
                    'numeric'
                ),
            )
        ),
        'mail_id' => array(
            'numeric' => array(
                'rule' => 'checkMailId',
            )
        ),
        'send_flag' => array(
            'numeric' => array(
                'rule' => array('boolean'),
            )
        ),
    );

    public function checkMailId($mail_id) {
        $deliver_mail = Configure::read('deliver_mail');
        if (isset($deliver_mail[$mail_id['mail_id']]) && ($deliver_mail[$mail_id['mail_id']]['forcesend'] != true)) {
            return true;
        }

        return false;
    }

    public function getMailDeliverySendFlag($ids, $mail_id) {
        $result = $this->find('all', array(
            "conditions" => array(
                'MailDelivery.user_id' => $ids,
                'MailDelivery.mail_id' => $mail_id
            ),
            "fields" => array(
                "MailDelivery.id",
                "MailDelivery.user_id",
                "MailDelivery.send_flag",
            )
        ));

        return $result;
    }
    public function getMailDeliverySetting($user, $mail_id) {
        if ($user['mail_confirm_flag'] === FLAG_OFF) {
            return FLAG_OFF;
        }
        $result = $this->find('first', array(
            "conditions" => array(
                'MailDelivery.user_id' => $user['id'],
                'MailDelivery.mail_id' => $mail_id,
                'MailDelivery.send_flag' => FLAG_OFF
            ),
            "fields" => array(
                "MailDelivery.user_id",
                "MailDelivery.send_flag",
            )
        ));

        $return = FLAG_ON;
        if (!empty($result) && $result['MailDelivery']['send_flag'] === FLAG_OFF) {
            $return = FLAG_OFF;
        }

        return $return;
    }

    public function getMailInfoText($id) {
        $txt = null;
        $deliver_mail = Configure::read('deliver_mail');
        if (isset($deliver_mail[$id]['mailinfotxt'])) {
            $txt = $deliver_mail[$id]['mailinfotxt'];
        }

        return $txt;
    }
}