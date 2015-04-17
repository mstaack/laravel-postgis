<?php namespace Phaza\LaravelPostgis\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Expression;
use Phaza\LaravelPostgis\Geometries\Geometry;

class Builder extends EloquentBuilder {

	use PostgisAttributeReplacer;

	public function first( $columns = [ '*' ] )
	{
		$this->replaceSelectColumns( $this->getPostgisFields(), $columns );

		return parent::first( $columns );
	}

	public function get( $columns = [ '*' ] )
	{
		$this->replaceSelectColumns( $this->getPostgisFields(), $columns );

		return parent::get( $columns );
	}

	public function paginate($perPage = null, $columns = [ '*' ])
	{
		$this->replaceSelectColumns( $this->getPostgisFields(), $columns );

		return parent::paginate( $perPage, $columns );
	}

	public function update( array $values )
	{
		foreach( $values as $key => &$value ) {
			if( $value instanceof Geometry ) {
				$value = $this->asWKT( $value );
			}
		}

		return parent::update( $values );
	}

	protected function getPostgisFields() {
		return $this->getModel()->getPostgisFields();
	}

	protected function getTable() {
		return $this->getModel()->getTable();
	}

	/**
	 * @param $field
	 * @return Expression
	 */
	protected function toText( $field )
	{
		return $this->getQuery()->raw( sprintf( 'ST_AsText(%s) AS %s', $field, $field ) );
	}


	protected function asWKT( Geometry $geometry )
	{
		return $this->getQuery()->raw( sprintf( "ST_GeogFromText('%s')", $geometry->toWKT() ) );
	}
}
