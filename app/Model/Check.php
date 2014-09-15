<?php

class Check extends AppModel
{
    private function getImageSizeForSmartResize($dstWidth, $dstHeight, $srcWidth, $srcHeight){
        $factor = min(($dstWidth / $srcWidth), ($dstHeight / $srcHeight));

        return array($factor * $srcWidth, $factor * $srcHeight);
    }

    public function createThumbnail( $path_destination_dir, $check_id ) {

        $path_saved_photo = $path_destination_dir . "/" . $check_id . ".jpg";
        $path_thumb_photo = $path_destination_dir . "/thumb_" . $check_id . ".jpg";

        // イメージサイズ取得
        list( $width, $height ) = getimagesize( $path_saved_photo );
   
        // サムネイル画像のサイズを指定
        list( $new_width, $new_height ) = $this->getImageSizeForSmartResize( 70, 70, $width, $height );
   
        // 新しい画像を生成
        $src = imagecreatefromjpeg( $path_saved_photo );
                
        // 画像領域の作成
        $image = imagecreatetruecolor( $new_width, $new_height );

        // サムネイル画像の生成
        imagecopyresampled( $image, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
        
        imagejpeg( $image, $path_thumb_photo );
        
        imagedestroy( $image );
        imagedestroy( $src );

        return $path_thumb_photo;
    }
}