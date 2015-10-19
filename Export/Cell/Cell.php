<?php
/**
 * Created by PhpStorm.
 * User: kirya
 * Date: 01.10.15
 * Time: 9:30
 */

namespace Export\Cell;

use Export\Style\CellStyle;

class Cell
{

    /**
     * @var CellStyle
     */
    private $_style;

    /**
     * @var mixed
     */
    private $_value;

    public static function create($value)
    {
        return new self($value);
    }

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->_value = isset($value) ? $value : '';
    }

    /**
     * @return CellStyle
     */
    public function getStyle()
    {
        return $this->_style;
    }

    /**
     * @param CellStyle $style
     */
    public function setStyle(CellStyle $style)
    {
        $this->_style = $style;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }




    public function getValue()
    {
        return $this->_value;
    }

    function __toString()
    {
        return (string)$this->getValue();
    }


}