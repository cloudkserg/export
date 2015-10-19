<?php
use Export\Schema\Schema as Schema;
use Export\Type as Type;
use Export\Cell\Cell;
/**
 * Description of ExportComponent
 *
 * @author art3mk4
 */
abstract class ExportComponent extends \CComponent
{
    //Текст
    protected $_text = '';

    public $charset;
    
    /**
     * _schema
     *
     * @var Schema
     */
    protected $_schema;


    /**
     * @var Cell[]
     */
    protected $_row = array();
    
    //расширение
    protected $_extension = '';


    /**
     * 
     * @param type $schema
     */
    public function setSchema(Schema $schema)
    {
        $this->_schema = $schema;
    }

    /**
     * addHeaders
     *
     * @return void
     */
    public function addHeaders()
    {
        $columns = $this->_schema->getColumns();

        $this->newRow();
        foreach ($columns as $key => $column) {
            $this->setField($column->getId(), $column->getLabelCell());
        }
        $this->saveRow();
    }


    /**
     * 
     * @return boolean
     */
    public function newRow()
    {
        $this->_row = array();
        return true;
    }

    /**
     * 
     * @param mixed $field
     * @param Cell $cell
     */
    public function setField($field, Cell $cell)
    {
        $this->_row[$field] = $cell;
    }

    /**
     * 
     * @return boolean
     */
    abstract public function saveRow();

    /**
     * @return int
     */
    private function getFirstColumn()
    {
        $names = $this->_schema->names;
        return !empty($names) ? array_keys($names)[0] : 0;
    }

    /**
     * 
     * @param Cell[]|mixed $string
     */
    public function addString($string)
    {
        if (!is_array($string)) {
            $string = array($this->getFirstColumn() => $string);
        }

        $this->newRow();
        foreach ($string as $key => $cell) {
            $this->setField($key, $this->prepareStringCell($cell));
        }
        $this->saveRow();
    }

    /**
     * @param $value
     * @return Cell
     */
    private function prepareStringCell($value)
    {
        if (!$value instanceof Cell) {
            return new Cell($value);
        }
        return $value;
    }

    /**
     * saveFile
     *
     * обязательно вызывается при последнем сохранении файла 
     * до этого можно вызывать update
     *
     * @param mixed $path
     * @param mixed $filename
     * @return void
     */
    public function saveFile($path, $filename)
    {
    }

    /**
     * updateFile
     *
     * вызывается при обновлении файла
     * когда к его содержимому просто добавляются новые строки
     *
     * @param mixed $path
     * @param mixed $filename
     * @return void
     */
    public function updateFile($path, $filename)
    {
    
    }
    
    /**
     * getText
     * 
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }


    /**
     * create
     *
     * @param mixed $type
     * @return ExportComponent
     */
    public static function create($type)
    {
        if ($type == Type::SPSS) {
            return new SpssComponent();
        } elseif ($type == Type::XLS) {
            return new XlsxComponent();
        } else {
            return new CsvComponent();
        }
    }
}
