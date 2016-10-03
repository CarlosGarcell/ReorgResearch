<?php

namespace App\Contracts;

interface PaymentRecordRepositoryInterface {
	public function getAll();
	public function getRecordCount();
	public function saveRecords($records);
	public function fetchRecords($recordIds);
	public function fetchRecordById($recordId);
}