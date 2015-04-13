<?php namespace Phaza\LaravelPostgis\Eloquent; 

trait PostgisAttributeReplacer {
	/**
	 * Run through all queried columns and convert geometry ones to WKT
	 *
	 * @param array $columns
	 */
	protected function replaceSelectColumns( array $pgisFields, array &$columns )
	{
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
}
