<?php
App::uses('AppModel', 'Model');
/**
 * SnsInfo Model
 *
 * @property User $User
 */
class SnsInfo extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

    //validation localizations
    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);

        $this->validate = array(
            'delete_flag' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                ),
            ),
        );
    }

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

    /**
     * Save SNS info for user
     *
     * @method saveInfoForUser
     * @param int $user_id
     * @param object $social_user_data
     * @return boolean
     * @author Mai Nhut Tan
     * @since 2013/09/12
     */
    public function saveInfoForUser($user_id = null, $sns_type = SOCIAL_TYPE_UNKNOWN, $social_user_data = null) {
        //no user id or no data
        if($user_id == null || $social_user_data == null){
            return false;
        }

        //find last social info of the user
        $last_info = $this->find(
            'first',
            array(
                'conditions' => array(
                    'sns_type' => $sns_type,
                    'user_id' => $user_id
                ),
                'recursive' => -1
            )
        );

        //update info
        try{
            //pre-process data
            if($sns_type == SOCIAL_TYPE_GOOGLE){
                $social_user_data->identifier = preg_replace('/^.*?id\=/', '', $social_user_data->identifier);
            }

            //TODO: implement auth_token data retrieve

            //update info
            if(!empty($last_info)){
                $last_info['SnsInfo']['sns_type'] = $sns_type;
                $last_info['SnsInfo']['id_sns'] = $social_user_data->identifier;
                $last_info['SnsInfo']['auth_token'] = '';
                $last_info['SnsInfo']['auth_token_secret'] = '';
            }else{
                $last_info = array(
                    'SnsInfo' => array(
                        'user_id' => $user_id,
                        'sns_type' => $sns_type,
                        'id_sns' => $social_user_data->identifier,
                        'auth_token' => '',
                        'auth_token_secret' => '',
                        'delete_flag' => FLAG_NOT_DELETED
                    )
                );
            }
        }catch(Exception $e){
            //return false for invalid $social_user_data type
            return false;
        }

        //save info to DB
        $result = $this->save($last_info, false);

        return $result ? true : false;
    }
    
    public function getUniqueUser($snsInfoList = array()) {
        $conditions = array();
        foreach($snsInfoList as $snsType => $idList) {

            $conditions[] = array(
                array('sns_type' => $snsType),
                array('id_sns' => $idList)
            );
        }
        return $this->User->SnsInfo->find('all', array(
            'fields' => array('user_id'),
            'conditions' => array(
                'or' => array(
                    $conditions
                )
            ),
            'group' => 'user_id'

        ));
    }
}
