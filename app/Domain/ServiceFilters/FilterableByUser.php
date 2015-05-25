<?php namespace Zeropingheroes\Lanager\Domain\ServiceFilters;

trait FilterableByUser {

	/**
	 * Filter by a given user
	 * @param  integer $userId  User's ID
	 * @return Collection       Collection of items
	 */
	public function filterByUser( $userId )
	{
		$this->model = $this->model->where( 'user_id', $userId );

		return $this;
	}

}