<?php

namespace App\Http;

class Pagination {
	private int $per_page = 0;
	private array $to_paginate = [];

	public  function __construct(int $per_page, array $to_paginate){
		$this->per_page = $per_page;
		$this->to_paginate = $to_paginate;
	}

	public function paginate() {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$page_first_result = ($page-1)*$this->per_page;
		$total = count($this->to_paginate);
		$number_of_page = ceil($total/$this->per_page);
		$page = max($page,1);
		$page = min($page,$total);
		$offset = ($page-1)*$this->per_page;
		if ($offset < 0) $offset = 0;
		return [self::slice($this->to_paginate,$offset,$this->per_page),$number_of_page];

	}

	private static function slice(array $array, int $start, int $end) {
		$array = array_slice($array,$start,$end);
		return $array;
	}
}