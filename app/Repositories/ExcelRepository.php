<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;

use Maatwebsite\Excel\Facades\Excel;

use App\Exceptions\InvalidTypeException;

use App\Contracts\ExcelRepositoryInterface;

class ExcelRepository implements ExcelRepositoryInterface {

	public function __construct() {

	}

	/**
	 * [exportFile Exports a file to XLS format based on the received data array.]
	 * @param  [array] $rowsData     [Data array that contains the rows of the Excel file]
	 * @param  [string] $fileName 	 [Name of the file to be exported]
	 * @return [Array]           	 [Contains the storage path and the file name]
	 */
	public function exportFile($rowsData = [], $fileName = 'PaymentsExport') {
		if (!is_array($rowsData)) throw new InvalidTypeException('Argument 1 must be of type array, ' . gettype($rowsData) . ' given');
		if (!is_string($fileName)) throw new InvalidTypeException('Argument 2 must be of type string, ' . gettype($fileName) . ' given');

		$appendCounter = 1;
		$fullFileName = date("Y-m-d") . '_' . $fileName;
		$filePath = storage_path('excel/exports') . '/' . $fullFileName . '.xls';

		/*
		Check whether the file we're trying to export exists.
		If it does, differentiate it by adding number to the end as in (1).
		 */
		while (File::exists($filePath)) {
			$fullFileName = date("Y-m-d") . '_' . $fileName . "($appendCounter)";
			$filePath = storage_path('excel/exports') . '/' . $fullFileName . '.xls';
			++$appendCounter;
		}
		

		// Store excel file in /storage/excel/exports
		return Excel::create($fullFileName, function($excel) use ($rowsData) {
			$excel->sheet('Payments', function($sheet) use($rowsData) {
				$sheet->fromArray($rowsData, null, 'A1', false);
			});
		})->store('xls', storage_path('excel/exports'));
	} 
}