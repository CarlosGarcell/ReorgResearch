<?php

namespace App\Repositories;

use App\PaymentRecord;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Contracts\PaymentRecordRepositoryInterface;

class PaymentRecordRepository implements PaymentRecordRepositoryInterface {

	public function __construct() {
		
	}

	/**
	 * [getAll description]
	 * @return [type] [description]
	 */
	public function getAll() {
		return PaymentRecord::all();
	}

	/**
	 * [getRecordCount description]
	 * @return [type] [description]
	 */
	public function getRecordCount() {
		return PaymentRecord::count();
	}

	/**
	 * [saveRecords description]
	 * @return [type] [description]
	 */
	public function saveRecords($records = []) {
		if (count($records) === 0) return [];

		DB::beginTransaction();

		$arrayOfInstances = array_map(function($record) {
			if (isset($record['name_of_third_party_entity_receiving_payment_or_transfer_of_value'])) {
				$record['name_third_party_entity_receiving_payment_or_transfer_of_value'] = $record['name_of_third_party_entity_receiving_payment_or_transfer_of_value'];
				unset($record['name_of_third_party_entity_receiving_payment_or_transfer_of_value']);
			}
			PaymentRecord::create($record);
			return $record;
		}, $records);

		DB::commit();

		return count($arrayOfInstances);
	}

	/**
	 * [fetchRecords description]
	 * @param  [type] $recordIds [description]
	 * @return [type]            [description]
	 */
	public function fetchRecords($recordIds = []) {
		return PaymentRecord::find($recordIds);
	}

	/**
	 * [searchRecordById description]
	 * @param  [type] $recordId [description]
	 * @return [type]           [description]
	 */
	public function fetchRecordById($recordId) {
		
	}
}