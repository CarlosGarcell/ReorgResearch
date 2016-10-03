<?php

namespace App\Repositories;

use allejo\Socrata\SodaClient;
use allejo\Socrata\SodaDataset;
use allejo\Socrata\SoqlQuery;

use Illuminate\Support\Facades\Log;

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
	 * [importDataset description]
	 * @param  integer $limit  [description]
	 * @param  integer $offset [description]
	 * @return [type]          [description]
	 */
	public function importRecords($limit = 1000, $offset = 0) {

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

	/**
	 * [importAllRecords description]
	 * @return [type] [description]
	 */
	public function importAllRecords() {
		$limit = 5000;
		$offset = 0;
		$records = [];
		$firstLoop = true;

		do {
			if ($firstLoop) {
				$records = $this->sodaDataSet->getDataset($this->soql->select()->limit($limit)->offset($offset));
				$firstLoop = false;
				self::saveRecords($records);
			}
			$offset += $limit;
			$records = $this->sodaDataSet->getDataset($this->soql->select()->limit($limit)->offset($offset));
			self::saveRecords($records);
		} while(count($records) > 0);
	}
}