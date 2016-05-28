<?php

namespace Simi\Crawler\Dto;


class CrawledHtmlData
{
    private $productId;

    private $headline;

    private $image;

    private $desc;

    /**
     * CrawledHtmlData constructor.
     * @param $productId
     * @param $headline
     * @param $image
     * @param $desc
     */
    public function __construct($productId, $headline, $image, $desc)
    {
        $this->productId = $productId;
        $this->headline = $headline;
        $this->image = $image;
        $this->desc = $desc;
    }


    /**
     * @return mixed
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    
}