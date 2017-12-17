<?php
$url = 'https://www.google.co.jp/search?q=%E6%B2%96%E7%B8%84%E3%80%80%E9%AB%98%E7%B4%9A%E3%83%9B%E3%83%86%E3%83%AB';
$file = file_get_contents($url);
$file = mb_convert_encoding($file, 'utf8', 'sjis-win');

libxml_use_internal_errors(TRUE); // html構文エラーを無視
$document = new DOMDocument();
$document->loadHTML($file);
$xpath = new DOMXPath($document);

foreach ($xpath->query('//div[@class="g"]') as $key => $node) {
    // 10件まで
    if ($key == 10) {
        break;
    }
    echo "<<< " . $xpath->evaluate('string(.//h3[@class="r"]/a)', $node) . " >>>" . PHP_EOL;
    echo $xpath->evaluate('string(.//cite)', $node) . PHP_EOL;
    echo '---------------' . PHP_EOL;
}
