<?php
namespace sys\lib;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * 读写 Excel
 */
class Excel
{
    private $filePath;      // Excel  目录
    private $objExcel;      // Excel  对象
    private $objSheet;      // Sheet  对象
    private $objWriter;     // writer 对象

    /**
     * Excel constructor.
     * @param String $path
     * @throws \Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    function __construct(String $path) {
        $this->filePath = $path;
        include_once('system/lib/PhpSpreadsheet/vendor/autoload.php');
        $this->objExcel = IOFactory::load($path);
        $this->objWriter = IOFactory::createWriter($this->objExcel, 'Xls');
        $this->objSheet = $this->objExcel->getActiveSheet();
    }

    /**
     * Excel destruct.
     */
    function __destruct() {
        // TODO: 看看這裏到底是什麽錯
//        if(is_object($this->objExcel)) $this->objExcel->disconnectobjSheets();
        unset($this->objExcel);
    }

    /**
     * 设置工作表
     */
    function setSheet() {
    }

    /**
     * 读取 Sheet 组成二维数组
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    function read() {
        $dataset = array();
        $highestRow = $this->objSheet->getHighestRow();
        $highestColumn = $this->objSheet->getHighestColumn();
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($column = "A"; $column <= $highestColumn; $column++) {
                $dataset[$row][] = str_replace(array("\r\n", "\r", "\n", " "), '', 
                $this->objSheet->getCell($column.$row)->getValue());
            }
        }
        return $dataset;
    }

    /**
     * 写单元格
     * @param String $point
     * @param String $value
     * @return \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     */
    function setCell(String $point, String $value) {
    	return $this->objSheet->setCellValue($point, $value);
    }

    /**
     * 用二维数组，覆盖对应单元格
     * @param array $value
     * @return bool|\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    function setArray(Array $value) {
        if(is_array($value)) return $this->objSheet->fromArray($value);
        return false;
    }

    /**
     * 保存修改
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    function save() {
        $this->objWriter->save($this->filePath);
    }
}