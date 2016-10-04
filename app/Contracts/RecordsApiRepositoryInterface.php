<?php

namespace App\Contracts;

interface RecordsApiRepositoryInterface {
	public function importRecords($limit,$offset);
}