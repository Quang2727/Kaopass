<?php

class ChecksController extends AppController
{
    public function create()
    {
        $data = array(
            'user_id' => $this->user_id,
            'shop_id' => $this->request->data['shop_id'],
        );

        $res = $this->Check->save( $data );
        
        $path_destination_dir  = Configure::read('Directory.Photo') . "/" . $data['shop_id'];

        if ( !is_dir( $path_destination_dir ) ) {
            mkdir( $path_destination_dir );
        }
        $path_destination_file = "/" . $this->Check->id . ".jpg";
        if ( move_uploaded_file($this->request->params['form']['photo']['tmp_name'], $path_destination_dir . $path_destination_file) ) {
            if ( $this->Check->createThumbnail( $path_destination_dir, $this->Check->id ) ) {
                return $this->responseOk($res);
            }
        }
        return $this->responseNg($res);
    }
}
