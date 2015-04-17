<?php namespace Phaza\LaravelPostgis\Eloquent;

trait PostgisAttributeReplacer {
	/**
	 * Run through all queried columns and convert geometry ones to WKT
	 *
	 * @param array $columns
	 */
	protected function replaceSelectColumns( array $pgisFields, array &$columns )
	{

		foreach( $columns as &$column ) {
			if( in_array( $column, $pgisFields ) ) {
				$column = $this->toText( $column );
			}
			elseif( $columns === $this->getTable() . '.*' ) {
				foreach( $pgisFields as $field => $type ) {
					$columns[] = $this->toText( $this->getTable() . $field );
				}
			}
			elseif( $column === '*' ) {
				foreach( $pgisFields as $field => $type ) {
					$columns[] = $this->toText( $field );
				}
			}
		}
	}
}
