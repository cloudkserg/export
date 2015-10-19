<?php
namespace Export;
/**
 * Type
 *
 * @version 1.0.0
 * @copyright Copyright 2011 by Kirya <cloudkserg11@gmail.com>
 * @author Kirya <cloudkserg11@gmail.com>
 */
class Type extends \ConstDirectoryModel
{

    const CSV = 'csv';
    const SPSS = 'sav';
    const XLS = 'xlsx';
    const PDF = 'pdf';

    /**
     * getTitles
     *
     * @return array
     */
    public function getTitles()
    {
        return array(
            self::SPSS => 'SPSS',
            self::CSV => 'CSV',
            self::XLS => 'XLS'

        );
    }




}
