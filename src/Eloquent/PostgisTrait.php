<?php namespace Phaza\LaravelPostgis\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Phaza\LaravelPostgis\Eloquent\Relations\BelongsToMany;
use Phaza\LaravelPostgis\Exceptions\PostgisFieldsNotDefinedException;
use Phaza\LaravelPostgis\Geometries\Geometry;

trait PostgisTrait {
	use PostgisAttributeReplacer;
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
				$value = $this->getConnection()->raw( sprintf( "ST_GeogFromText('%s')", $value->toWKT() ) );
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

	public function belongsToMany($related, $table = null, $foreignKey = null, $otherKey = null, $relation = null) {
		/**
		 * Illuminate\Database\Eloquent\Relations\BelongsToMany
		 */
		$object = parent::belongsToMany( $related, $table, $foreignKey, $otherKey, $relation );


		return new BelongsToMany($object->getQuery(), $object->getParent(), $object->getTable(), $object->getForeignKey()
			, $object->getOtherKey(), $object->getRelationName());
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
