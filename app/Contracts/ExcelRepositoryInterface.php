<?php

namespace App\Contracts;

interface ExcelRepositoryInterface {
	public function exportFile($data, $fileName);
}