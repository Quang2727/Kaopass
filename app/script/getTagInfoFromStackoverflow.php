<?php

/**
 * stackoverflowからタグとその説明文をCSVファイルで取得する。
 * 2014/03/26時点のHTMLソースから取得しているため、変更があった場合は取得条件を変更しないと取得できなくある可能性大。
 * 
 * きれいにパースできず(HTMLの記述に難がある)、waringがでるがデータ取得には支障がない。
 * 実行ディレクトリの/tmp/ディレクトリにファイルを出力する。
 */

$tagList[] = array();
for($i=1;$i<=20;$i++) {
    $url = "http://stackoverflow.com/tags?page=".$i."&tab=popular";
    $source = file_get_contents($url);

    $dom = new DOMDocument();
    $dom->loadHTML($source);
    $xml = $dom->saveXML();
    $xmlObject = simplexml_load_string($xml);
    $array = json_decode(json_encode($xmlObject), true);
    #var_dump($xmlObject->xpath("/body/div"));
    $tableObject = $xmlObject->body->div[4]->div[1]->div->div[2]->table->tr;
    foreach($tableObject as $value) {
        $trObject = $value->td;
        foreach($trObject as $value) {
            $tagList[] = (string) $value->a;
        }
    }
}

$fp = fopen('/tmp/stackoverflow_tags.csv', 'w');
fputcsv($fp, array('tag_name','explain'));
foreach($tagList as $value) {
    $url = "http://stackoverflow.com/questions/tagged/".$value;
    $source = file_get_contents($url);

    $dom = new DOMDocument();
    $dom->loadHTML($source);
    $xml = $dom->saveXML();
    $xmlObject = simplexml_load_string($xml);
    $array = json_decode(json_encode($xmlObject), true);
    #var_dump($xmlObject->xpath("/body/div"));
    $explainText = (string) $xmlObject->body->div[4]->div[1]->div->div[1]->div[1]->div->p;

    fputcsv($fp, array($value,$explainText));
    sleep(2);    
}
fclose($fp);    

