<?php namespace Phaza\LaravelPostgis\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Expression;
use Phaza\LaravelPostgis\Geometries\Geometry;

class Builder extends EloquentBuilder {
	public function first( $columns = [ '*' ] )
	{
		$this->replaceSelectColumns( $columns );

		return parent::first( $columns );
	}

	public function get( $columns = [ '*' ] )
	{
		$this->replaceSelectColumns( $columns );

		return parent::get( $columns );
	}

	/**
	 * Run through all queried columns and convert geometry ones to WKT
	 *
	 * @param array $columns
	 */
	protected function replaceSelectColumns( array &$columns )
	{

		/**
		 * @var PostgisModel $model
		 */
		$model = $this->getModel();

		$pgisFields = $model->getPostgisFields();

		if( count( $columns ) === 1 and $columns[0] === '*' ) {
			foreach( $pgisFields as $field => $type ) {
				$columns[] = $this->toText( $field );
			}
		}
		else {
			foreach( $columns as &$column ) {
				if( in_array( $column, $pgisFields ) ) {
					$column = $this->toText( $column );
				}
			}
		}
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

	protected function asWKT( Geometry $geometry )
	{
		return $this->getQuery()->raw( sprintf( 'ST_GeomFromText(%s, 4326)', $geometry->toWKT() ) );
	}


	/**
	 * @param $field
	 * @return Expression
	 */
	protected function toText( $field )
	{
		return $this->getQuery()->raw( sprintf( 'ST_AsText(%s) AS %s', $field, $field ) );
	}

}
