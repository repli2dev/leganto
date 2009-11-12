<?php
/**
 * @author Jan Papousek
 */
class SearchPresenter extends BasePresenter
{

	public function renderBook($query, $start = 0, $limit = 10, $column = NULL, $order = "ASC") {
		// If the query is empty, skip the searching 
		if (empty($query)) {
			return;
		}
		
	}

}
