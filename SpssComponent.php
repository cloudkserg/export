<?php
use Export\Schema\TextColumn as TextColumn;
use Export\Schema\NumericColumn as NumericColumn;
use Symfony\Component\Yaml\Dumper as Dumper;
/**
 * SpssComponent
 *
 * @version 1.0.0
 * @copyright Copyright 2011 by Kirya <cloudkserg11@gmail.com>
 * @author Kirya <cloudkserg11@gmail.com>
 */
class SpssComponent extends ExportComponent
{

    const KEY_PYTHON_ADDED_VALUE_TO_STRING = 'Q';
    const TASK_FILE = 'spss_task_';
    const COMMAND = 'spss.py';
    const DEFAULT_WIDTH = 10;

    /**
     * _taskFile
     *
     * @var string
     */
    private $_taskFile;

    /**
     * _rows
     *
     * @var array
     */
    private $_rows = array();

    /**
     * _varNames
     *
     * @var array
     */
    private $_varNames = array();

    /**
     * _varTypes
     *
     * @var array
     */
    private $_varTypes = array();

    /**
     * _varLabels
     *
     * @var array
     */
    private $_varLabels = array();

    /**
     * _formats
     *
     * @var array
     */
    private $_formats = array();


    /**
     * _valueLabels
     *
     * @var array
     */
    private $_valueLabels = array();

    /**
     * _columnWidths
     *
     * @var array
     */
    private $_columnWidths = array();


    /**
     * _tempFile
     *
     * @var string
     */
    private $_tempFile;



    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {

        $uniq = round(microtime(true));
        $this->_taskFile = self::TASK_FILE . $uniq . '.yml';
        $this->_tempFile = $this->_taskFile . '.temp';
    }


    /**
     * isFirstAlpha
     *
     * @param mixed $name
     * @return boolean
     */
    private function isFirstAlpha($name)
    {
        return preg_match("/^[A-Za-zА-Яа-я]$/", substr($name, 0, 1));
    }

    private function getPythonKey($name)
    {
        $name = strtolower($name);
        if (empty($name) or !$this->isFirstAlpha($name)) {
            return self::KEY_PYTHON_ADDED_VALUE_TO_STRING . $name;
        }

        return $name;
    }



    public function addHeaders()
    {
        $this->genVarNames();
        $this->genVarLabels();
        $this->genVarTypes();
        $this->genFormats();
        $this->genValueLabels();
        $this->genColumnWidths();
    }


    /**
     * genVarNames
     *
     * @return void
     */
    private function genVarNames()
    {
        foreach ($this->_schema->names as $value) {
            $this->_varNames[] = $this->getPythonKey($value);
        }
    }

    /**
     * genColumnWidths
     *
     * @return void
     */
    private function genColumnWidths()
    {
        $columns = $this->_schema->columns;
        foreach ($columns as $column) {
            $this->_columnWidths[$this->getPythonKey($column->id)] = self::DEFAULT_WIDTH;
        }
    }

    /**
     * genVarTypes
     *
     * @return void
     */
    private function genVarTypes()
    {
        $columns = $this->_schema->columns;
        foreach ($columns as $column) {
            if ($column instanceof TextColumn) {
                $this->_varTypes[$this->getPythonKey($column->id)] = 250;
            } else {
                $this->_varTypes[$this->getPythonKey($column->id)] =  0;
            }
        }
    }

    /**
     * genVarLabels
     *
     * @return void
     */
    private function genVarLabels()
    {
        $columns = $this->_schema->columns;
        foreach ($columns as $column) {
            $this->_varLabels[$this->getPythonKey($column->id)] = $column->label;
        }
    }

    /**
     * genFormats
     *
     * @return void
     */
    private function genFormats()
    {
    
        $columns = $this->_schema->columns;
        foreach ($columns as $column) {
            if ($column instanceof TextColumn) {
                $this->_formats[$this->getPythonKey($column->id)] = 'A250';
            } else {
                $this->_formats[$this->getPythonKey($column->id)] =  'F6.0';
            }
        }
    }

    /**
     * genValueLabels
     *
     * @return void
     */
    private function genValueLabels()
    {
        $columns =$this->_schema->columns;
        foreach ($columns as $column) {
            if (!$column->isEmptyList()) {
                $this->_valueLabels[$this->getPythonKey($column->id)] = $column->getList();
            }
        }
    
    }


    /**
     * saveFile
     *
     * @param string $path
     * @param string $name
     * @return void
     */
    public function saveFile($path, $name)
    {
        $this->createYml($path, $name);
        $this->execPythonSpss($path);
    }


    /**
     * getPythonCommand
     *
     * @return string
     */
    private function getPythonCommand()
    {
        return \Yii::getPathOfAlias('admin.resources') . '/' . self::COMMAND;
    }

    private function execPythonSpss($path)
    {
        $command = $this->getPythonCommand();
        $fullCommand = $command . ' ' . $this->_taskFile;
        print $fullCommand;
        $result = exec($fullCommand, $output);
    }

    /**
     * updateFile
     *
     * @Ничего не делаем - весь файл только
     *
     * @param mixed $path
     * @param mixed $name
     * @return void
     */
    public function updateFile($path, $name)
    {
        return;
    }

    public function saveRow()
    {
        $row = array();

        foreach ($this->_schema->names as $name) {
            if (isset($this->_row[$name])) {
                $row[] = (string)$this->_row[$name];         
            } else {
                $row[] = '';
            }
        }

        $this->_rows[] = $row;
    }


    /**
     * createYml
     *
     * @param string $path
     * @return void
     */
    private function createYml($path, $file)
    {
        $settings = array(
            'output' => $path . '/' . $file,
            'varNames' => $this->_varNames,
            'varTypes' => $this->_varTypes,
            'varLabels' => $this->_varLabels,
            'formats' => $this->_formats,
            'valueLabels' => $this->_valueLabels,
            'columnWidths' => $this->_columnWidths,
            'records' => $this->_rows
        );

        $dumper = new Dumper();

        file_put_contents($path . '/' . $this->_taskFile, $dumper->dump($settings, 4));
    }



}
