<?php

namespace App\Repositories;

use allejo\Socrata\SodaClient;
use allejo\Socrata\SodaDataset;
use allejo\Socrata\SoqlQuery;

use Illuminate\Support\Facades\Log;

use App\Exceptions\InvalidTypeException;
use App\Exceptions\LimitOutOfBoundsException;

use App\Contracts\RecordsApiRepositoryInterface;
use App\Contracts\PaymentRecordRepositoryInterface;

class SodaApiRepository implements RecordsApiRepositoryInterface {

	protected $endpoint;
	protected $dataset;
	protected $sodaClient;
	protected $sodaDataSet;
	protected $soql;
	protected $paymentRecordRepository;

	public function __construct($endpoint, $dataset, PaymentRecordRepositoryInterface $paymentRecordRepository) {
		$this->endpoint = $endpoint;
		$this->dataset = $dataset;
		$this->sodaClient = new SodaClient($endpoint);
		$this->sodaDataSet = new SodaDataset($this->sodaClient, $dataset);
		$this->soql = new SoqlQuery();
		$this->paymentRecordRepository = $paymentRecordRepository;
	}

	/**
	 * [importDataset Imports records from the Open Payments API]
	 * 
	 * @param  integer $limit  [Number of records to fetch from the API (Max is 50,000 per request)]
	 * @param  integer $offset [Integer that indicates from which record on will the API fetch records]
	 *
	 * @throws [InvalidTypeException] [If any of the parameters is not an integer, throw an InvalidTypeException]
	 * @throws [LimitOurOfBoundsException] [If the requested limit exceeds 50000, throw a LimitOutOfBoundsException]
	 * 
	 * @return [Array]          [Array containing how many records were saved and the current total count in the DB]
	 */
	public function importRecords($limit = 1000, $offset = 0) {
		if(!is_numeric($limit)) throw new InvalidTypeException('Argument 1 must be an integer, ' . gettype($limit) . ' given');
		if(!is_numeric($offset)) throw new InvalidTypeException('Argument 2 must be an integer, ' . gettype($offset) . ' given');

		if ($limit < 0 || $offset < 0) return [];

		if ($limit > 50000) throw new LimitOutOfBoundsException('The amount of requested records exceeds 50,000');

		$recordInstances = [];

		Log::info('Limit: ' . $limit);
		Log::info('Offset: ' . $offset);
		Log::info('Retrieving ' . $limit . ' records...');

		$records = $this->sodaDataSet->getDataset($this->soql->select()->limit($limit)->offset($offset));

		Log::info('Retrieved Records = ' . count($records));

		if(count($records) > 0) {
			$savedRecordsCount = $this->paymentRecordRepository->saveRecords($records);
			return ['savedRecordsCount' => $savedRecordsCount, 'dbRecordCount' => $this->paymentRecordRepository->getRecordCount()];
		}

		return ['savedRecordsCount' => 0, 'dbRecordCount' => $this->paymentRecordRepository->getRecordCount()];
	}
}