<?php
/**
 * 共通バリデートチェック
 */
class CommonValidatesBehavior extends ModelBehavior {

    /**
     * 全角半角文字数をチェック
     * 指定した文字数より多い場合はエラーとする
     *
     * @param $model     modelオブジェクト
     * @param $data      入力フォーム値
     * @param $minLength    最小文字数
     * @param $maxLength    最大文字数 
文字数
     */
    public function rangeLength(&$model, $form_data, $minLength, $maxLength) {

        // 配列（[フォーム名]=>入力値）から、入力値のみを取得
        $str = array_shift($form_data);
        $real_length = mb_strlen($str, 'UTF-8');

        if($real_length >= $minLength && $real_length <= $maxLength) {
            return true;
        }
        return false;
    }
}
