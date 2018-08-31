// APIからの返却値チェック
exports.validBody = function (body) {
  if (undefined === body) {
    console.log('通信エラーが発生しました。');
    return false;
  }

  if (!body) {
    console.log('内容が取得できませんでした。');
    return false;
  }

  return true;
}

// JSONパース
exports.parseJSON = function (body) {
  try {
    bodyObj = JSON.parse(body);
  } catch (e) {
    console.log('JSON変換に失敗しました。body=' + body + ', error=' + e);
    return false;
  }

  if (bodyObj.error !== undefined && bodyObj.error !== '') {
    console.log('エラーが発生しました。下記をご確認下さい。');
    console.log(bodyObj);
    return false;
  }

  return bodyObj;
}

// UTF-8変換
exports.toUTF8 = function (str) {
  var jschardet = require('jschardet');
  var Iconv = require('iconv').Iconv;

  //文字コード判定
  var detectResult = jschardet.detect(str);

  if ('utf-8' === detectResult.encoding || 'ascii' === detectResult.encoding) {
    // 変換不要
    return str;
  }

  var iconv = new Iconv(detectResult.encoding,'UTF-8//TRANSLIT//IGNORE');

  str = iconv.convert(str).toString();

  return str;
}