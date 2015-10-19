<?php
/**
 * Created by PhpStorm.
 * User: kirya
 * Date: 01.10.15
 * Time: 9:55
 */

namespace Export\Style;

/**
 * Class CellStyle
 * @package Export\Style
 */
class CellStyle
{

    public $color;

    public $colorText;

    public $width;

    public $border = false;

    public $mergers = array();


    public static function create($color = null, $colorText = null, $width = null)
    {
        return new self($color, $colorText, $width);
    }

    function __construct($color = null, $colorText = null, $width = null, $border = null)
    {
        $this->color = $color;
        $this->colorText  = $colorText;
        $this->width = $width;
        $this->border = $border;
    }


    /**
     * @param boolean $border
     * @return $this
     */
    public function setBorder($border)
    {
        $this->border = $border;
        return $this;
    }

    /**
     * @param $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @param mixed $colorText
     * @return $this
     */
    public function setColorText($colorText)
    {
        $this->colorText = $colorText;
        return $this;
    }

    /**
     * @param $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function addMerger(Merger $merger)
    {
        $this->mergers[] = $merger;
    }



}