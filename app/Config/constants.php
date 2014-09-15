<?php

define('CONSUMER_KEY', 'XLBK6CSS8fUbNOih5W9scsieB');
define('CONSUMER_SECRET', 'eIDPMvEnxHvh8tM9mGxHIPFwY5WvRUWAZvXvWjxT9Y5jnZWkLi');
define('ACCESS_TOKEN', '2288909150-U0sCikPOGvC5VxptQDwCFxpRtHxyArLG5jlOVlk');
define('ACCESS_TOKEN_SECRET', '78fJfq8l3GwB39u1PUslBZXwpsf6Sjvg0YlL0aTdE42wH');
define('OAUTH_CALLBACK', 'localhost/twitteroauth/callback.php');

define('FACEBOOK', 1);

define('TWITTER', 2);

define('ENGLISH', 0);
define('JAPAN', 1);

define('NOT_LOCATION', 0);

define('UPDATE', 1);
define('ADDSHOP', 2);
define('UPLOAD', 3);
define('ADD_INPUT_SHOP', 4);
define('MOVE_SHOP', 5);
define('CUT_SHOP', 6);
define('CHANGE_FOLDER', 7);
define('RENAME', 8);

define('SHARE', 1);
define('NO_SHARE', 0);


define('FOLDER_NORMAL', 0);
define('FOLDER_SECRET', 2);
define('FOLDER_PUBLIC', 1);

define('SHOP_NORMAL', 0);
define('SHOP_PUBLIC', 1);

define('LIMIT_COMMENT', 5);


define('URL_BANNER', "http://img.moppy.jp/pub/pc/friend/728x90-2.jpg");


define('CREATED', 0);

define('EDITED', 1);

define('DELETED', 2);

define('MOVE', 3);

define('ADD', 4);

define('READ_NOTI', 1);

define('NOT_READ_NOTI', 0);

Configure::write('NOTIFICATION', array(
    0 => "作成しました。",  // created folder
    1 => "編集しました。", //  Edited shop
    2 => "編集しました。", // Deleted shop
    3 => "編集しました。", //  Move shop
    4 => "add shop", //  add shop in folder
));


define('MY_FOLDER', 0);

define('NO_MY_FOLDER', 1);

define('NOTI_USER', 0);

define('NOTI_SYSTEM', 1);