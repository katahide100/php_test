<?php
//URLを指定する
$url='https://premier.no1s.biz/';
//POST用データ作成
$data=array
(
    'email'=>'micky.mouse@no1s.biz',
    'password'=>'micky',
);
//クッキー保存ファイル作成
$cookie=tempnam(sys_get_temp_dir(),'cookie_');

############ トークン取得のための接続 ################

//cUrl初期化
$curl=curl_init();
//オプションにURLを設定する
curl_setopt($curl,CURLOPT_URL,$url);
//文字列で結果を返させる
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//クッキーを書き込むファイルを指定
curl_setopt($curl,CURLOPT_COOKIEJAR,$cookie);
//URLにアクセスし、結果を文字列として返す
$html=curl_exec($curl);
//cURLのリソースを解放する
curl_close($curl);
//Document初期化
$dom=new DOMDocument();
//html文字列を読み込む（htmlに誤りがある場合エラーが出るので@をつける）
@$dom->loadHTML($html);
//XPath初期化
$xpath=new DOMXPath($dom);
//inputのtypeがhiddenの要素をとってくる
$node=$xpath->query('//input[@type="hidden"]');
foreach($node as $v)
{
    //POST用のデータに追加する
    $data[$v->getAttribute('name')]=$v->getAttribute('value');
}

############ ログインのための接続 ################

//cUrl初期化
$curl=curl_init();
//オプションにURLを設定する
curl_setopt($curl,CURLOPT_URL,$url);
//メソッドをPOSTに設定
curl_setopt ($curl,CURLOPT_POST,true);
//POSTデータ設定
curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
//クッキーを読み込むファイルを指定
curl_setopt($curl,CURLOPT_COOKIEFILE,$cookie);
//Locationをたどる
curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);
//クッキーを書き込むファイルを指定
curl_setopt($curl,CURLOPT_COOKIEJAR,$cookie);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//URLにアクセスし、結果を表示させる
$ret = curl_exec($curl);
//cURLのリソースを解放する
curl_close($curl);

############ データ取得のための接続 ################

$strCsvData = '';
$i = 1;
while (true) {
    //cUrl初期化
    $curl=curl_init();
    curl_setopt($curl,CURLOPT_URL,$url . 'admin?page=' . $i);
    //メソッドをPOSTに設定
    curl_setopt ($curl,CURLOPT_POST,true);
    //POSTデータ設定
    //curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
    //クッキーを読み込むファイルを指定
    curl_setopt($curl,CURLOPT_COOKIEFILE,$cookie);
    //Locationをたどる
    curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);
    //文字列で結果を返させる
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    //URLにアクセスし、結果を表示させる
    //curl_exec($curl);

    //URLにアクセスし、結果を文字列として返す
    $html=curl_exec($curl);
    //cURLのリソースを解放する
    curl_close($curl);

    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    libxml_use_internal_errors(TRUE); // html構文エラーを無視

    //Document初期化
    $dom=new DOMDocument();
    //html文字列を読み込む（htmlに誤りがある場合エラーが出るので@をつける）
    @$dom->loadHTML($html);
    //XPath初期化
    $xpath=new DOMXPath($dom);
    //inputのtypeがhiddenの要素をとってくる
    $node=$xpath->query('//h1')->item(0);

    if ($node->nodeValue === 'Error') {
        // エラーだった場合は終了
        break;
    }
    $colNum = 3; // 項目数
    foreach ($xpath->query('//table[@class="table table-striped"]/tr') as $key => $node) {
        if ($key == 0) {
            // 項目行は飛ばす
            continue;
        }
        $arrData = explode("\n",$node->nodeValue);
        foreach ($arrData as $data) {
            $data = trim($data); // 空白削除
            if (!empty($data)) {
                $strCsvData .= '"' . $data . '",';
            }
        }
        $strCsvData .= "\n";
    }

    $i++;
}

//テンポラリファイルを削除
unlink($cookie);

// CSV出力
if (!empty($strCsvData)) {
    $fp = fopen('test.csv', 'w');
    $strCsvData = mb_convert_encoding($strCsvData, "UTF-8", "auto");
    fwrite($fp, $strCsvData);
    fclose($fp);
}
