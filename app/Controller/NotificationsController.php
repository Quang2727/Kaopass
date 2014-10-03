<?php

App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');

class NotificationsController extends AppController {

    public $uses = array('User', 'Notification');

    function index() {
       
    }

}
