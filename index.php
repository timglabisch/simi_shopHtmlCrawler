<?php

use Simi\Crawler\HtmlDataCrawler;
use Simi\Crawler\ProductDataCrawler;

require __DIR__ . '/vendor/autoload.php';


if (!isset($_SERVER['argv'][1]) || !is_dir($_SERVER['argv'][1])) {
    echo "benutze index.php [PFAD]";
    die(1);
}

$path = $_SERVER['argv'][1];


$productCrawler = new ProductDataCrawler($path);
$productCrawler->run();

$htmlDataCrawler = new HtmlDataCrawler($path);
$htmlDataCrawler->run();


$products = $productCrawler->getProducts();
$htmlData = $htmlDataCrawler->getHtmlData();

$handle = fopen('foo.csv', 'w');

$i = 0;
foreach ($products as $id => $product) {

    if (!isset($htmlData[$id])) {
        echo "kein HTML zu " . $id . "\n";
        continue;
    }

    $html = $htmlData[$id];

    echo sprintf("%s %s %s\n", $i++, $id, $html->getImage());

    fputcsv(
        $handle,
        [
            'id' => $id,
            'image' => $html->getImage()
        ]
    );
}

fclose($handle);
