<?php

namespace Simi\Crawler;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Simi\Crawler\Dto\CrawledHtmlData;
use Symfony\Component\DomCrawler\Crawler;

class HtmlDataCrawler
{
    /**
     * @var CrawledHtmlData[]
     */
    private $htmlData = [];

    private $path;

    /**
     * HtmlDataCrawler constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    private function getHtmlDataForPath($path) {
        $html = file_get_contents($path);

        $id_matches = [];
        preg_match_all("/\'UID\', \\'([a-zA-Z0-9]+)/im", $html, $id_matches);
        $productId = (isset($id_matches[1], $id_matches[1][0]) ? $id_matches[1][0] : null);

        $html = strip_tags(preg_replace(['/<\\?.*(\\?>|$)/Us'], '', $html), '<img><div><a><b><i><strong><ul><li>');

        if (!$productId) {
            return null;
        }

        return new CrawledHtmlData(
            $productId,
            '',
            $this->extractImage(new Crawler($html)),
            ''
        );
    }

    private function extractImage(Crawler $crawler)
    {
        $sel = $crawler->filter('img.iz_originalimg');
        if (!$sel) {
            return null;
        }

        $node = $sel->getNode(0);
        if (!$node) {
            return null;
        }

        return $node->getAttribute('src');
    }

    public function run() {


        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path), RecursiveIteratorIterator::SELF_FIRST);
        foreach($objects as $name => $object){

            if (!strpos($name, '.php')) {
                continue;
            }

            if (!is_file($name)) {
                continue;
            }

            echo ".";
            $data = $this->getHtmlDataForPath($name);

            if (!$data) {
                continue;
            }

            $this->htmlData[$data->getProductId()] = $data;
        };
    }

    /**
     * @return CrawledHtmlData[]
     */
    public function getHtmlData()
    {
        return $this->htmlData;
    }

}