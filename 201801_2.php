<?php
// エクセルファイルを zip として処理
$zip = new ZipArchive();
$zip->open( 'data.xlsx' );
// 1シート目のxml取得
$xml_sheet = simplexml_load_string( $zip->getFromName( 'xl/worksheets/sheet1.xml' ) );
// sharedStrings.xml を取得
$xml_sst = simplexml_load_string( $zip->getFromName( 'xl/sharedStrings.xml' ) );
// 結果格納用
$result = array();

$startRow = 4; // 実データ開始行
// 行ループ
foreach( $xml_sheet->sheetData->row as $row ) {
  // セルループ
  foreach( $row->c as $cell ) {
    // セルの名前。"A1" とか "B2" とか
    $cell_no = (string)$cell->attributes()->r;

    $str = $cell_no;
    preg_match('/^([A-Z]+)(.+)$/sD', $str, $matches);
 
    $cell_no_1 = $matches[1];
    $cell_no_2 = $matches[2];

    if ($cell_no_2 >= $startRow) {

        // セルのタイプ。文字列が設定されている場合は "s"
        $cell_type = (string)$cell->attributes()->t;
        // セルの値。$cell_type が "s" の場合は
        // sharedStrings.xml -> si の index値が設定される。
        $cell_value = (int)$cell->v;
        // "s" の場合は sharedStrings.xml から取得
        if ( $cell_type === 's' ) {
        $cell_value = (string)$xml_sst->si[ (int)$cell->v ]->t;
        }
        
        $result[ $cell_no_2 ][] = $cell_value;
    }
  }
}
$zip->close();

echo '名前        国語   数学   英語   社会   理科   合計点' . PHP_EOL;

foreach ($result as $arrData) {
    $total = 0;
    foreach ($arrData as $key => $data) {
        if ($key == 1) { // 名前
            echo ' ' . $data;
        }elseif ($key >= 2) { // 点数
            echo '   ' . $data;
            $total = $total + $data;
        }else{
            echo $data;
        }
    }
    echo '   ' . $total; // 合計
    echo PHP_EOL;
}
