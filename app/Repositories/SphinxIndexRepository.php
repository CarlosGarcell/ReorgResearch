<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

use sngrl\SphinxSearch\SphinxSearch;

use App\Contracts\SearchIndexRepositoryInterface;

class SphinxIndexRepository implements SearchIndexRepositoryInterface {

	/**
	 * [$sphinxIndexInstance description]
	 * @var [type]
	 */
	protected $sphinxIndexInstance;

	/**
	 * [$indexName description]
	 * @var [type]
	 */
	protected $indexName;

	public function __construct() {
		$this->sphinxIndexInstance = new SphinxSearch();
	}

	/**
	 * [search description]
	 * @param  string $criteria [description]
	 * @return [type]           [description]
	 */
	public function search($criteria = '') {
		if(!is_string($criteria)) throw new InvalidTypeException('Argument 1 must be of type string, ' . gettype($criteria) . ' given');
		if ($criteria === '') return [];

		$sphinxSearch = new SphinxSearch();

		$sphinxSearch->limit(1000, 0, 1000, 0);

		Log::info($sphinxSearch->getErrorMessage());

    	return $sphinxSearch->search($criteria, env('DATASET_SEARCH_INDEX_NAME'))->query();
	}

	/**
	 * [autocomplete description]
	 * @param  string $keyword [description]
	 * @return [type]          [description]
	 */
	public function autocomplete($keyword = '') {
		if ($keyword === '') return [];

		$sphinxSearch = new SphinxSearch();

		$suggestions = DB::table('suggest')->where('keyword', 'LIKE', $keyword . '%')->limit(10)->orderBy('freq', 'desc')->get();

		if (count($suggestions) > 0) {
			return $suggestions->map(function($item, $key) {
				return $item->keyword;
			})->toArray();
		}

		return [];
	}

	/**
	 * [getIndexTotalCount description]
	 * @return [type] [description]
	 */
	public function getIndexTotalCount() {
		$sphinxSearch = new SphinxSearch();

		return $sphinxSearch->search('', env('DATASET_SEARCH_INDEX_NAME'))->query()['total_found'];
	}

	/**
	 * [indexDataset description]
	 * @return [type] [description]
	 */
	public function indexDataset() {
		Log::info('Datset Indexing Result = ' . Artisan::call('index:dataset'));
	}

	/**
	 * [generateKeywordSuggestions description]
	 * @return [type] [description]
	 */
	public function generateKeywordSuggestions() {
		Log::info('Keyword Generation Process Result = ' . Artisan::call('generate:keywords_dictionary'));
	}
}