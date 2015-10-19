<?php
namespace Export\Schema;
use Export\Cell\Cell;
use Export\Style\CellStyle;
/**
 * Column
 *
 * @version 1.0.0
 * @copyright Copyright 2011 by Kirya <cloudkserg11@gmail.com>
 * @author Kirya <cloudkserg11@gmail.com>
 */
abstract class Column extends \CComponent
{
    const TEXT = 'text';
    const NUMERIC = 'numeric';

    /**
     * _id
     *
     * @var string
     */
    protected $_id;

    /**
     * _label
     *
     * @var string
     */
    protected $_label;

    /**
     * @var CellStyle
     */
    protected $_labelStyle;
    
    /**
     * _list
     *
     * @var array
     **/
    protected $_list = array();

    /**
     * @param $type
     * @param $id
     * @param $label
     * @return NumericColumn|TextColumn
     * @throws \Exception
     */
    public static function create($type, $id, $label)
    {
        if ($type == self::TEXT) {
            return new TextColumn($id, $label);
        } elseif ($type == self::NUMERIC) {
            return new NumericColumn($id, $label);
        }

        throw new \Exception('Unknown type of Export\Column');
    }

    /**
     * __construct
     *
     * @param mixed $id
     * @param mixed $label
     * @return void
     */
    public function __construct($id, $label = null)
    {
        $this->_id = $id;
        $this->_label = $label;
   }

    /**
     * getId
     *
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * getLabel
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }



    /**
     * setList
     *
     * @param array $values
     * @return void
     */
    public function setList(array $values)
    {
        $this->_list = $values;
    }

    /**
     * getList
     *
     * @return array
     */
    public function getList()
    {
        return $this->_list;
    }

    /**
     * isEmptyList
     *
     * @return boolean
     */
    public function isEmptyList()
    {
        return empty($this->_list);
    }

    /**
     * @return CellStyle
     */
    public function getLabelStyle()
    {
        return $this->_labelStyle;
    }

    /**
     * @param CellStyle $labelStyle
     * @return $this
     */
    public function setLabelStyle(CellStyle $labelStyle)
    {
        $this->_labelStyle = $labelStyle;
        return $this;
    }

    /**
     * @return Cell
     */
    public function getLabelCell()
    {
        $cell =  Cell::create($this->_label);
        if (isset($this->_labelStyle)) {
            $cell->setStyle($this->_labelStyle);
        }
        return $cell;
    }




    /**
     * createNew
     *
     * @param mixed $inputId
     * @param mixed $inputLabel
     * @return Column
     */
    public function createNew($inputId = null, $inputLabel = null)
    {
        $id = isset($inputId) ? $inputId : $this->id;
        $label = isset($inputLabel) ? $inputLabel : $this->label;

        $newItem = new static($id, $label);
        $newItem->setList($newItem->getList());
        $newItem->setLabelStyle($this->getLabelStyle());

        return $newItem;
        
    }

}
