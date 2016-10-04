<?php

namespace App\Repositories;

use App\PaymentRecord;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Exceptions\InvalidTypeException;

use App\Contracts\PaymentRecordRepositoryInterface;

class PaymentRecordRepository implements PaymentRecordRepositoryInterface {

	public function __construct() {
		
	}

	/**
	 * [getAll Returns all the registered records in the DB]
	 * @return [Collection] [A collection of all DB records]
	 */
	public function getAll() {
		return PaymentRecord::all();
	}

	/**
	 * [getRecordCount Get the total number of registered records in the DB]
	 * @return [integer] [Number of registered records in the DB]
	 */
	public function getRecordCount() {
		return PaymentRecord::count();
	}

	/**
	 * [saveRecords Saves the fetched record to the DB]
	 * 
	 * @param  array  $records [Aray containing the records to be saved in the DB]
	 *
	 * @throws [InvalidTypeException] [A passed parameter does not match the type it requires to have by the function]
	 * 
	 * @return [Array|Integer]          [An integer of how many records were processed, otherwise, an empty array if the $records array is empty]
	 */
	public function saveRecords($records = []) {
		if (!is_array($records)) throw new InvalidTypeException('Argument 1 must be of type array, ' . gettype($records) . ' given');

		if (count($records) === 0) return [];

		DB::beginTransaction();

		/*
		Iterate over every record in the $records array. A special case exists with the API names of the records,
		and since the DB table columns owe their names to the keys from the API array, we must validate against this.
		The name 'name_of_third_party_entity_receiving_payment_or_transfer_of_value' is too long for MySQL to accept it as
		a valid column name, so we've changed it to 'name_of_third_party_entity_receiving_payment_or_transfer_of_value', but in 
		order to be able to leverage the create method of Laravel's models, we must make sure that the keys in the array completely
		match the names of the DB table columns, thus the existence of the if condition to check whether this column is set, if so, a new
		key is added to the record array to hold the valeu of the former key and then the former key us unset from the array to avoid issues.
		 */
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
	 * [fetchRecords Returns a Collection that contains all records that matched the ids]
	 * @param  [Array] $recordIds 	   [Array of record ids to search in the DB]
	 * @return [Collection]            [Collection of all records that matched the record ids]
	 */
	public function fetchRecords($recordIds = []) {
		if(!is_array($recordIds)) throw new InvalidTypeException('Argument 1 must be of type array, ' . gettype($recordIds) . ' given');

		if(count($recordIds) === 0) return [];

		return PaymentRecord::find($recordIds);
	}
}