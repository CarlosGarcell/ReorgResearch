<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;

use Maatwebsite\Excel\Facades\Excel;

use App\Exceptions\ExcelDataTypeException;

class ExcelRepository {

	public function __construct() {

	}

	/**
	 * [exportFile description]
	 * @param  [type] $rowsData     [description]
	 * @param  [type] $fileName 	[description]
	 * @return [type]           	[description]
	 */
	public function exportFile($rowsData = [], $fileName = 'PaymentsExport') {
		if (!is_array($rowsData)) throw new ExcelDataTypeException('Argument 1 must be of type array, ' . gettype($rowsData) . ' given');

		$fileExists = true;
		$appendCounter = 1;
		$fullFileName = date("Y-m-d") . '_' . $fileName;
		$filePath = storage_path('excel/exports') . '/' . $fullFileName . '.xls';

		// Differentiate files by adding a number in between parenthesis at the in, as in (1)
		while (File::exists($filePath)) {
			$fullFileName = date("Y-m-d") . '_' . $fileName . "($appendCounter)";
			$filePath = storage_path('excel/exports') . '/' . $fullFileName . '.xls';
			++$appendCounter;
		}
		

		// Create excel file and store in /storage/excel/exports
		Excel::create($fullFileName, function($excel) use ($rowsData) {
			$excel->sheet('Payments', function($sheet) use($rowsData) {
				$sheet->fromArray($rowsData, null, 'A1', false);
			});
		})->store('xls', storage_path('excel/exports'));

		return $filePath;
	} 
}