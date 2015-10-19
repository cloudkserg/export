<?php
/**
 * Created by PhpStorm.
 * User: kirya
 * Date: 02.10.15
 * Time: 16:52
 */

namespace Export\Style;


use Export\Schema\Schema;

class Merger
{

    /**
     * @var array
     */
    private $_columnIds;

    public function __construct(array $columnIds)
    {
        $this->_columnIds = $columnIds;
    }

    /**
     * @param $columnId
     */
    public function addColumn($columnId)
    {
        $this->_columnIds[] = $columnId;
    }


    /**
     * @param Schema $schema
     * @return array
     */
    public function getNumberColumns(Schema $schema)
    {
        $numbers = [];
        foreach ($this->_columnIds as $id) {
            $key = $schema->findColumnKey($id);
            if (isset($key)) {
                $numbers[] = $key;
            }
        }

        return $numbers;
    }


}