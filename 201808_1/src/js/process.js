var webclient = require("request");
var commonModule = require('../js/common.js');

// スプレッドシートキー
var spreadSheetKey = "11BCnspCt2Mut3nhc4WMY6CYTd0zF9C3eCzsk1AEpKLM";
// 取得範囲（{シート名}!{範囲セル}）
var range = "sales!A1:E6";
// APIキー
var apiKey = "******** 要設定 **********";

webclient.get({
  url: "https://sheets.googleapis.com/v4/spreadsheets/" + spreadSheetKey + "/values/" + range,
  qs: {
    key: apiKey,
  }
}, function (error, response, body) {
  
  // 返却値チェック
  if(!commonModule.validBody(body)) {
    return false;
  }

  // JSONパース
  bodyObj = commonModule.parseJSON(body);
  if (!bodyObj) {
    return false;
  }

  var str = '';
  bodyObj.values.forEach(function(row){
    row.forEach(function(val){
      str += "'" + val + "',";
    });
    str += '\n';
  });

  //結果出力
  console.log(commonModule.toUTF8(str));
});

