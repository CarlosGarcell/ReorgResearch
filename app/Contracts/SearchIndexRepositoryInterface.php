<?php

namespace App\Contracts;

interface SearchIndexRepositoryInterface {
	public function search($criteria);
	public function getIndexTotalCount();
}