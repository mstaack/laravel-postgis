<?php namespace Phaza\LaravelPostgis\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Exceptions\PostgisFieldsNotDefinedException;
use Phaza\LaravelPostgis\Geometries\Geometry;

class PostgisModel extends Model {
	/**
	 * Create a new Eloquent query builder for the model.
	 *
	 * @param  \Illuminate\Database\Query\Builder $query
	 * @return \Phaza\LaravelPostgis\Eloquent\Builder
	 */
	public function newEloquentBuilder( $query )
	{
		return new Builder( $query );
	}

	protected function performInsert( EloquentBuilder $query, array $options = [] )
	{
		foreach( $this->attributes as $key => &$value ) {
			if( $value instanceof Geometry ) {
				$value = $this->getConnection()->raw( sprintf( "ST_GeomFromText('%s', 4326)", $value->toWKT() ) );
			}
		}

		return parent::performInsert( $query, $options );
	}

	public function setRawAttributes( array $attributes, $sync = false )
	{
		$pgfields = array_keys($this->getPostgisFields());

		foreach($attributes as $attribute => &$value) {
			if(in_array($attribute, $pgfields) && is_string($value) && strlen($value) >= 10) { // POINT(0 0) = 10 chars
				$geometryType = Geometry::getWKTClass($value);
				$value = call_user_func($geometryType.'::fromWKT', $value);
			}
		}

		parent::setRawAttributes( $attributes, $sync );
	}


	public function getPostgisFields()
	{
		if( property_exists( $this, 'postgisFields' ) ) {
			return $this->postgisFields;
		}

		else {
			throw new PostgisFieldsNotDefinedException( __CLASS__ . ' has to define $postgisFields' );
		}

	}
}
