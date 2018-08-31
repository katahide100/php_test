# 事前準備

### node.jsバージョン

※下記バージョンでしか動作しません。
「n」もしくは「nvm」コマンドをインストールし、下記バージョンを指定して下さい。

`v8.x.x` （xは適宜変更）

動かない場合は下記バージョンを指定

`v8.11.4`

### 下記ファイルを書き換える（必須）

`$ vi src/js/process.js`

9行目にスプレッドシートのAPIキーを設定する

`var apiKey = "******** 要設定 **********";`

### 下記実行(少し時間かかります)

`$ npm install`

iconvのinstallに失敗する場合は、下記を実行してから`npm install`して下さい

```
$ wget http://people.centos.org/tru/devtools-2/devtools-2.repo -O /etc/yum.repos.d/devtools-2.repo
$ yum install devtoolset-2-gcc devtoolset-2-binutils
$ yum install devtoolset-2-gcc-c++ devtoolset-2-gcc-gfortran
$ scl enable devtoolset-2 bash
$ gcc --version
```

# メイン処理実行

下記コマンドを実行します。

`$ npm run 201808_1`

# テストコード実行

開発時に使用したものです。動作検証にご活用ください。

`$ npm run 201808_1_test`

