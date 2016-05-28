<?php

namespace Simi\Crawler\Dto;

class CrawledProduct
{
    private $path;

    private $productData = [];

    /**
     * CrawledProduct constructor.
     * @param $path
     * @param array $productData
     */
    public function __construct($path, \StdClass $productData)
    {
        $this->path = $path;
        $this->productData = $productData;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getProductData()
    {
        return $this->productData;
    }

}