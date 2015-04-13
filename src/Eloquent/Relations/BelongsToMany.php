<?php namespace Phaza\LaravelPostgis\Eloquent\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany as BaseBelongsToMany;
use Phaza\LaravelPostgis\Eloquent\PostgisAttributeReplacer;

class BelongsToMany extends BaseBelongsToMany {

	use PostgisAttributeReplacer;

	protected function getSelectColumns( array $columns = [ '*' ] )
	{
		$pgFields = $this->getParent()->getPostgisFields();

		$this->replaceSelectColumns( $pgFields, $columns );

		return parent::getSelectColumns( $columns );
	}

	protected function toText( $field ) {

		$table = $this->related->getTable();

		$newField = sprintf( 'ST_AsText(%s.%s) AS %s.%s', $table, $field, $table, $field );

		return $this->getBaseQuery()->raw( $newField );
	}

}
