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
		// If no relationship name was passed, we will pull backtraces to get the
		// name of the calling function. We will use that function name as the
		// title of this relation since that is a great convention to apply.
		if (is_null($relation))
		{
			$relation = $this->getBelongsToManyCaller();
		}

		// First, we'll need to determine the foreign key and "other key" for the
		// relationship. Once we have determined the keys we'll make the query
		// instances as well as the relationship instances we need for this.
		$foreignKey = $foreignKey ?: $this->getForeignKey();

		$instance = new $related;

		$otherKey = $otherKey ?: $instance->getForeignKey();

		// If no table name was provided, we can guess it by concatenating the two
		// models using underscores in alphabetical order. The two model names
		// are transformed to snake case from their default CamelCase also.
		if (is_null($table))
		{
			$table = $this->joiningTable($related);
		}

		// Now we're ready to create a new query builder for the related model and
		// the relationship instances for the relation. The relations will set
		// appropriate query constraint and entirely manages the hydrations.
		$query = $instance->newQuery();

		return new BelongsToMany($query, $this, $table, $foreignKey, $otherKey, $relation);
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
