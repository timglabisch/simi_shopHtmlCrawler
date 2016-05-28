<?php

namespace Simi\Crawler;


use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Simi\Crawler\Dto\CrawledProduct;

class ProductDataCrawler
{

    private $path;

    /** @var CrawledProduct[] */
    private $products = [];

    /**
     * ProductDataCrawler constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @param $path
     * @return CrawledProduct | null
     */
    private function getProduct($path)
    {
        if (strpos(file_get_contents($path), '$product_data') === false) {
            return null;
        }

        ob_start();
        @require  $path;
        ob_end_clean();

        return isset($product_data) ? new CrawledProduct($path, $product_data) : null;
    }

    public function run() {

        define('SHOP_TO_DATE', 'foo');

        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path), RecursiveIteratorIterator::SELF_FIRST);
        foreach($objects as $name => $object){

            if (!is_file($name)) {
                continue;
            }

            $data = $this->getProduct($name);

            if (!$data) {
                continue;
            }

            if (!isset($data->getProductData()->uid)) {
                continue;
            }

            $this->products[$data->getProductData()->uid] = $data;
        }
    }

    /** @return CrawledProduct[] */
    public function getProducts()
    {
        return $this->products;
    }

}