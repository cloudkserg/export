<?php

/**
 * Description of PdfComponent
 *
 * @author art3mk4
 */
class PdfComponent extends ExportComponent
{
    
    /**
     * Массив текста
     * @var type 
     */
    private $_data = array();

    /**
     * Excel объект
     * @var type 
     */
    private $_objPHPExcel;

    /**
     * Счётчик колонок
     * @var type 
     */
    private $_columnIterator = 0;

    /**
     * Счётчик строк
     * @var type 
     */
    private $_rowIterator = 0;
    
    //Расширение
    protected $_extension = \Export\Type::PDF;


    /**
     * 
     * @param type $data
     */
    public function addRow($data)
    {
        $this->_data = array_merge($this->_data, $data);
    }

    /**
     * 
     */
    public function saveRow()
    {
        $tempData = array();
        foreach ($this->_schema as $field) {
            if (isset($this->_row[$field])) {
                $tempData[$field] = $this->_row[$field];
            } else {
                $tempData[$field] = "";
            }
        }
        
        //Загружаем Xls
        if ($this->loadPHPExcel()) {
            $this->loadPHPExcel();
            $this->_objPHPExcel = new PHPExcel();
        }

        $this->_objPHPExcel->getProperties()
             ->setCreator("ixtlan")
             ->setLastModifiedBy("ixtlan")
             ->setTitle("Office 2007 XLSX Test Document")
             ->setSubject("Office 2007 XLSX Test Document")
             ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
             ->setKeywords("office 2007 openxml php")
             ->setCategory("result file");
        $this->_objPHPExcel->setActiveSheetIndex(0);
        $this->_rowIterator++;
        if (!empty($this->_data)) {
            foreach ($this->_data as $item) {
                $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($this->_columnIterator, $this->_rowIterator, $item);
                $this->_columnIterator++;
            }
            $this->_data = array();
            $this->_rowIterator++;
        }
        $this->_columnIterator = 0;
        foreach ($tempData as $key => $value) {
            $this->_objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($this->_columnIterator, $this->_rowIterator, $value);
            $this->_columnIterator++;
        }
        $this->loadYii();
    }

    /**
     * 
     * @param type $file
     * @param type $pdf
     */
    public function saveFile($path, $name)
    {
        $filename = "{$name }." . $this->_extension;
        $fullname = $path . "/{$filename}";
        $objWriter = PHPExcel_IOFactory::createWriter($this->_objPHPExcel, 'PDF');
        $objWriter->writeAllSheets();
        $objWriter->save($fullname);
        return $filename;
    }

    /**
     *
     * @param type $msg
     * @throws CHttpException 
     */
    private function exitError($msg)
    {
        $this->loadYii();
        throw new CHttpException (404, $msg);
        exit();
    }

    /* private loadPHPExcel() {{{ */
    /**
     * loadPHPExcel
     *
     * @access private
     * @return void
     */
    private function loadPHPExcel()
    {
        $path  = Yii::getPathOfAlias('application.extensions.excel');
        spl_autoload_unregister(array('YiiBase','autoload'));
        
        if (defined('PHPEXCEL_ROOT')) {
            return false;
        }
        define('PHPEXCEL_ROOT', $path . '/');
        require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
        require_once($path .  "/PHPExcel.php");
        
        return true;
    }
    /* }}} */
    
    /* private unloadYii() {{{ */
    /**
     * unloadYii
     *
     * @access private
     * @return void
     */
    private function unloadYii()
    {
        spl_autoload_unregister(array('YiiBase','autoload'));
        return true;
    }
    
    /* }}} */

    /* private loadYii() {{{ */
    /**
     * loadYii
     *
     * @access private
     * @return void
     */
    private function loadYii()
    {
        spl_autoload_register(array('YiiBase', 'autoload'));
        return true;
    }
    /* }}} */
}