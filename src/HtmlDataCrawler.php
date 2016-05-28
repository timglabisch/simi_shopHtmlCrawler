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

        $matches = [];
        preg_match_all("/\'UID\', \\'([a-zA-Z0-9]+)/im", $html, $matches);

        $productId = (isset($matches[1], $matches[1][0]) ? $matches[1][0] : null);

        if (!$productId) {
            return null;
        }

        $crawler = new Crawler($html);

        return new CrawledHtmlData(
            $productId,
            '',
            $this->extractImage($crawler),
            ''
        );
    }

    public function run() {


        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path), RecursiveIteratorIterator::SELF_FIRST);
        foreach($objects as $name => $object){

            if (!is_file($name)) {
                continue;
            }

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

    /**
     * @param $crawler
     */
    private function extractImage(Crawler $crawler)
    {
        $sel = $crawler->filter('.pictureframe img');
        if (!$sel) {
            return null;
        }

        $node = $sel->getNode(0);
        if (!$node) {
            return null;
        }

        return $node->getAttribute('src');
    }

}