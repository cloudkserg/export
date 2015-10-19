<?php
namespace Export\Schema;
/**
 * Schema
 *
 * @version 1.0.0
 * @copyright Copyright 2011 by Kirya <cloudkserg11@gmail.com>
 * @author Kirya <cloudkserg11@gmail.com>
 */
class Schema extends \CComponent
{

    /**
     * _columns
     *
     * @var Column[]
     */
    private $_columns;

    /**
     * __construsct
     *
     * @param array $columns
     * @return void
     */
    public function __construct(array $columns=array())
    {
        $this->_columns = $columns;
    }

    /**
     * @param $name
     * @return int|null
     */
    public function findColumnKey($name)
    {
        foreach ($this->_columns as $key => $column) {
            if ($column->getId() == $name) {
                return $key;
            }
        }
        return null;
    }

    /**
     * getNames
     *
     * @return array
     */
    public function getNames()
    {
        return \CHtml::listData($this->_columns, 'id', 'id');
    }

    /**
     * getLabels
     *
     * @return array
     */
    public function getLabels()
    {
        return \CHtml::listData($this->_columns, 'id', 'label');
    }

    /**
     * setColumns
     *
     * @param array $columns
     * @return void
     */
    public function setColumns(array $columns)
    {
        $this->_columns = $columns;
    }

    /**
     * addColumn
     *
     * @param Column $column
     * @return void
     */
    public function addColumn(Column $column)
    {
        $this->_columns[$column->id] = $column;
    }

    /**
     * addColumns
     *
     * @param array $columns
     * @return void
     */
    public function addColumns(array $columns)
    {
        foreach ($columns as $column) {
            $this->_columns[$column->id] = $column;
        }
    }

    /**
     * getColumns
     *
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->_columns;
    }


}
