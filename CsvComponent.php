<?php

/**
 * Description of CsvComponent
 *
 * @author art3mk4
 */
class CsvComponent extends ExportComponent
{

    const WIN1251 = 'CP1251';
    
    //Расширение
    protected $_extension = \Export\Type::CSV;

    /**
     * 
     * @param type $value
     * @return string
     */
    private function formatField($value)
    {
        
        $formatValue = str_replace('"', "'", $value);
        $formatValue = '"' . $formatValue . '"';

        return $formatValue;
    }

    /**
     * 
     * @return boolean
     */
    public function saveRow()
    {
        $names = $this->_schema->names;
        $tempText = '';
        foreach ($names as $field) {
            if (isset($this->_row[$field])) {
                    $tempText .= $this->formatField((string)$this->_row[$field]) . ';';
                } else {
                    $tempText .=  '"";';
                }
        } 
        $this->_text .= $tempText . "\r\n";
        return true;
    }


    /**
     * 
     * @param type $filename
     */
    public function saveFile($path, $name)
    {
        $nameWithoutExt = basename($name, "." . $this->_extension);
        $filename = "{$nameWithoutExt }." . $this->_extension;
        $fullname = $path . "/{$filename}";
        $this->encodingText();
        file_put_contents($fullname, $this->_text, FILE_APPEND);

        $this->_text = '';
        return $filename;
    }

    /**
     * 
     * @return string
     */
    public function getExtension()
    {
        return $this->_extension;
    }

    /**
     * updateFile
     * 
     * @param unknown $path
     * @param unknown $name
     * @return string
     */
    public function updateFile($path, $name)
    {
        $nameWithoutExt = basename($name, "." . $this->_extension);
        $filename = "{$nameWithoutExt }." . $this->_extension;
        $fullname = $path . "/{$filename}";
        $this->encodingText();
        file_put_contents($fullname, $this->_text, FILE_APPEND);

        $this->_text = '';
        return $filename;
    }

    /**
    
    /**
     * encodingText
     */
    private function encodingText()
    {
        if ($this->charset == self::WIN1251) {
            $this->_text = iconv('UTF-8', 'CP1251//TRANSLIT', $this->_text);
        }
    }
}
