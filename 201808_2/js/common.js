
var x  = 300 ; // 初期座標（横軸）
var y  = 228 ; // 初期座標（縦軸）
var leftMax = 633; // 左最大座標
var rightMax = 2; // 右最大座標
var pv =   30 ; // 1回の移動量
var spUp = 2; // 何歩歩いたら速度アップするか
var spMax = 2; // 何段階までアップするか(あげすぎるとバグるので注意)
var imageDir = 'images/';
var images = { // 画像リスト
  'right' : [
    'mario_1.png', // 右初期画像
    'mario_2.png', // 右移動画像1
    'mario_3.png' // 右移動画像2
  ],
  'left' : [
    'mario_4.png', // 左初期画像
    'mario_5.png', // 左移動画像1
    'mario_6.png' // 左移動画像2
  ]
}

var currentKeyCd = ''; // 押下中のキーコード
var stop = false;
var type = ''; // 右か左か

// 対応ブラウザ（とりあえずIE6以上はJQUERYサポートなので対応ブラウザとする。）
var supportBrowser = [
  'chrome',
  'safari',
  'opera',
  'firefox',
  'ie6',
  'ie7',
  'ie8',
  'ie9',
  'ie10',
  'ie11'
];

// キーが押されたか判定
$(document).on('keydown', 'html', function(e) {
    if (support && currentKeyCd === '') { // 押下中のキーがない場合のみ実行
      switch (e.keyCode) {
        case 39: // 右
          type = 'right';
          break;
        case 37: // 左
          type = 'left';
          break;
        default: // 上記以外のキーだった場合は何もしない
          return e;
      }
      stop = false;
      currentKeyCd = e.keyCode;
      runMove(type); //アニメーションを実行
    }
});

// キーが離れたか判定
$(document).on('keyup', 'html', function(e) {
    if (support && currentKeyCd !== '') {
      stop = true; // アニメーションを停止
    }
});

var cnt = 0;
var multipliedNum = 1; // 速度変更に使用

// アニメーション実行関数（再帰処理）
function runMove(type)
{
  // 外枠判定（外枠にあたったらそれ以上進めない）
  if ('right' === type) {
    if (x < leftMax) {
      x = x + pv;
    }
    if (x > leftMax) {
      x = leftMax;
    }
  } else if ('left' === type) {
    if (x > rightMax) {
      x = x - pv;
    }
    if (x < rightMax) {
      x = rightMax;
    }
  }

  // 歩く画像取得
  var image1 = imageDir + images[type][1];
  var image2 = imageDir + images[type][2];

  $('#image').animate({
    left:x,
    top:y
  }, 100 * multipliedNum, 'swing', function(){
    $("#image img").attr("src", image1);
  }).animate({
    left:x,
    top:y
  }, 100 * multipliedNum, 'swing', function(){
    $("#image img").attr("src", image2);
    cnt++;
    if (stop) { // キーが離れていた場合
      // setTimeoutを停止
      clearTimeout(repeat);
      // 各変数初期化（もっといい場所がある？）
      currentKeyCd = '';
      cnt = 0;
      multipliedNum = 1;

      // イメージ初期化
      $("#image img").attr("src", imageDir + images[type][0]);

      return false;
    }
    if (cnt < spUp * spMax + 1 && ( cnt % spUp ) == 0){ // 特定の条件で速度アップ
      multipliedNum = multipliedNum / 2;
    }
  });
  
  repeat = setTimeout('runMove("' + type + '")', 200 * multipliedNum); //アニメーションを繰り返す間隔
}

/**
 *  ブラウザ名を取得
 *  
 *  @return     ブラウザ名(ie6、ie7、ie8、ie9、ie10、ie11、chrome、safari、opera、firefox、unknown)
 *
 */
var getBrowser = function(){
  var ua = window.navigator.userAgent.toLowerCase();
  var ver = window.navigator.appVersion.toLowerCase();
  var name = 'unknown';

  if (ua.indexOf("msie") != -1){
      if (ver.indexOf("msie 6.") != -1){
          name = 'ie6';
      }else if (ver.indexOf("msie 7.") != -1){
          name = 'ie7';
      }else if (ver.indexOf("msie 8.") != -1){
          name = 'ie8';
      }else if (ver.indexOf("msie 9.") != -1){
          name = 'ie9';
      }else if (ver.indexOf("msie 10.") != -1){
          name = 'ie10';
      }else{
          name = 'ie';
      }
  }else if(ua.indexOf('trident/7') != -1){
      name = 'ie11';
  }else if (ua.indexOf('chrome') != -1){
      name = 'chrome';
  }else if (ua.indexOf('safari') != -1){
      name = 'safari';
  }else if (ua.indexOf('opera') != -1){
      name = 'opera';
  }else if (ua.indexOf('firefox') != -1){
      name = 'firefox';
  }
  return name;
}

/**
*  対応ブラウザかどうか判定
*  
*  @param  browsers    対応ブラウザ名を配列で渡す(ie6、ie7、ie8、ie9、ie10、ie11、chrome、safari、opera、firefox)
*  @return             サポートしてるかどうかをtrue/falseで返す
*
*/
var isSupported = function(browsers){
  var thusBrowser = getBrowser();
  for(var i=0; i<browsers.length; i++){
      if(browsers[i] == thusBrowser){
          return true;
          exit;
      }
  }
  return false;
}

var support = isSupported(supportBrowser);
if (!support) {
  alert('ブラウザがサポートしていません。対応ブラウザ：' + supportBrowser);
}

