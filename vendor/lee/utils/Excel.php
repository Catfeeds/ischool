<?php
/**
 * Created by PhpStorm.
 * User: huhaibo
 * Date: 2015/11/9 0009
 * Time: 下午 16:01
 * excel处理帮助类
 **/

namespace lee\utils;
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'IOFactory.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'Cell.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'Writer' . DIRECTORY_SEPARATOR . 'Excel5.php';
class Excel {

	/**
	 * @param $excel_path
	 * @param string $encode
	 * 将指定的excel转换为数组
	 */
	function read_to_arr($filename, $begin_row, $encode = 'utf-8') {

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($filename);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
		$excelData = array();
		$index = 0;
		for ($row = $begin_row; $row <= $highestRow; $row++) {
			for ($col = 0; $col < $highestColumnIndex; $col++) {
				$excelData[$index][] = (string) $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
			}
			$index++;
		}
		return $excelData;
	}

	function write_to_file($settings_arr, $data) {
		$savePath = $settings_arr['savePath'];
		$title = $settings_arr['title'];
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()
			->setCreator("ischool");

		foreach ($data as $row => $record) {
			$col = 0;
			foreach ($record as $key => $cell) {
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($col, $row + 1, $cell);
				$col++;
			}

		}
		$objPHPExcel->createSheet();
		$objPHPExcel->getActiveSheet()->setTitle($title);

		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
		$objWriter->save($savePath);
		return 0;

	}
}