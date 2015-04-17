<?php namespace Phaza\LaravelPostgis\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Phaza\LaravelPostgis\Geometries\Geometry;

class Builder extends EloquentBuilder {

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


	protected function asWKT( Geometry $geometry )
	{
		return $this->getQuery()->raw( sprintf( "ST_GeogFromText('%s')", $geometry->toWKT() ) );
	}
}
