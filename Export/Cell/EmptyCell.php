<?php
/**
 * Created by PhpStorm.
 * User: kirya
 * Date: 01.10.15
 * Time: 9:30
 */

namespace Export\Cell;



class EmptyCell extends Cell
{

    public function __construct()
    {
    }


    public function getStyle()
    {
        return null;
    }

    public function getValue()
    {
        return '';
    }

}