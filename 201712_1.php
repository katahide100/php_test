<?php
$api_key = "UhuBsj54DbRnnHGQYaPywHmAi" ;	// APIキー
$api_secret = "e1zSaGoQorVKzyW5W7U8LaAmkUsf3nnjdIHCxh45Y03bnnBj86" ;	// APIシークレット
$access_token = "42566916-5CI2BDtmiUQdWFKtTY6ajgxmLlYjURI4ftW2K88h3" ;	// アクセストークン
$access_token_secret = "YHfdFB1RJxIcHQ6XOriX0GZhmZmFpNmI5wegaBIRDflIU" ;	// アクセストークンシークレット
$request_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json' ;	// エンドポイント
$request_method = 'GET' ;

// リクエストオプション
$option = array(
	'screen_name' => '@realDonaldTrump' ,
	'count' => 10 ,
	'tweet_mode' => 'extended',
) ;

// キーを作成する (URLエンコードする)
$signature_key = rawurlencode( $api_secret ) . '&' . rawurlencode( $access_token_secret ) ;

// 署名用
$signature = array(
	'oauth_token' => $access_token ,
	'oauth_consumer_key' => $api_key ,
	'oauth_signature_method' => 'HMAC-SHA1' ,
	'oauth_timestamp' => time() ,
	'oauth_nonce' => microtime() ,
	'oauth_version' => '1.0' ,
) ;

// オプションと署名パラメータをマージ
$params = array_merge( $option , $signature ) ;

ksort( $params ) ;

$request_params = http_build_query( $params , '' , '&' ) ;

$request_params = str_replace( array( '+' , '%7E' ) , array( '%20' , '~' ) , $request_params ) ;

// 変換した文字列をURLエンコードする
$request_params = rawurlencode( $request_params ) ;

// リクエストメソッドをURLエンコードする
$encoded_request_method = rawurlencode( $request_method ) ;
 
// リクエストURLをURLエンコードする
$encoded_request_url = rawurlencode( $request_url ) ;
 
// リクエストメソッド、リクエストURL、パラメータを[&]で繋ぐ
$signature_data = $encoded_request_method . '&' . $encoded_request_url . '&' . $request_params ;

// キー[$signature_key]とデータ[$signature_data]を利用して、HMAC-SHA1方式のハッシュ値に変換する
$hash = hash_hmac( 'sha1' , $signature_data , $signature_key , TRUE ) ;

// base64エンコードして、署名[$signature]が完成する
$signature = base64_encode( $hash ) ;

// パラメータの連想配列、[$params]に、作成した署名を加える
$params['oauth_signature'] = $signature ;

// パラメータの連想配列を[キー=値,キー=値,...]の文字列に変換する
$header_params = http_build_query( $params , '' , ',' ) ;

$header = [
	'Authorization: OAuth ' . $header_params
];

// パラメータをURLの末尾に追加
$request_url .= '?' . http_build_query( $option ) ;

// cURLを使ってリクエスト
$curl = curl_init() ;
curl_setopt( $curl, CURLOPT_URL , $request_url ) ;
curl_setopt( $curl, CURLOPT_HEADER, true ) ; 
curl_setopt( $curl, CURLOPT_CUSTOMREQUEST , $request_method ) ;
curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER , false ) ;
curl_setopt( $curl, CURLOPT_RETURNTRANSFER , true ) ;
curl_setopt( $curl, CURLOPT_HTTPHEADER , $header ) ;	
curl_setopt( $curl, CURLOPT_TIMEOUT , 5 ) ;
$res1 = curl_exec( $curl ) ;
$res2 = curl_getinfo( $curl ) ;
curl_close( $curl ) ;

// 取得データ
$json = substr( $res1, $res2['header_size'] ) ;

// JSONを変換
$res_params = json_decode( $json, true ) ;	// 配列に変換

foreach ($res_params as $res) {
	$created_at = date('Y年m月d日 H時i分s秒', strtotime($res['created_at']));
	echo "<<< " . $created_at . " >>>". PHP_EOL;
	if (isset($res['retweeted_status'])) {
		echo $res['retweeted_status']['full_text']. PHP_EOL;
	} else {
		echo $res['full_text']. PHP_EOL;
	}
	echo '----------------------------'. PHP_EOL;
}
