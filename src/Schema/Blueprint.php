<?php namespace Phaza\LaravelPostgis\Schema;

class Blueprint extends \Phaza\LaravelPostgres\Schema\Blueprint {
	/**
	 * Add a point column on the table
	 *
	 * @param      $column
	 * @param null $srid
	 * @param int  $dimensions
	 * @param bool $typmod
	 * @return \Illuminate\Support\Fluent
	 */
	public function point($column, $srid = null, $dimensions = 2, $typmod = true) {
		return $this->addCommand('point', compact('column', 'srid', 'dimensions', 'typmod'));
	}

	/**
	 * Add a multipoint column on the table
	 *
	 * @param      $column
	 * @param null $srid
	 * @param int  $dimensions
	 * @param bool $typmod
	 * @return \Illuminate\Support\Fluent
	 */
	public function multipoint($column, $srid = null, $dimensions = 2, $typmod = true) {
		return $this->addCommand('multipoint', compact('column', 'srid', 'dimensions', 'typmod'));
	}

	/**
	 * Add a polygon column on the table
	 *
	 * @param      $column
	 * @param null $srid
	 * @param int  $dimensions
	 * @param bool $typmod
	 * @return \Illuminate\Support\Fluent
	 */
	public function polygon($column, $srid = null, $dimensions = 2, $typmod = true) {
		return $this->addCommand('polygon', compact('column', 'srid', 'dimensions', 'typmod'));
	}

	/**
	 * Add a multipolygon column on the table
	 *
	 * @param      $column
	 * @param null $srid
	 * @param int  $dimensions
	 * @param bool $typmod
	 * @return \Illuminate\Support\Fluent
	 */
	public function multipolygon( $column, $srid = null, $dimensions = 2, $typmod = true )
	{
		return $this->addCommand( 'multipolygon', compact( 'column', 'srid', 'dimensions', 'typmod' ) );
	}

	/**
	 * Add a linestring column on the table
	 *
	 * @param      $column
	 * @param null $srid
	 * @param int  $dimensions
	 * @param bool $typmod
	 * @return \Illuminate\Support\Fluent
	 */
	public function linestring($column, $srid = null, $dimensions = 2, $typmod = true) {
		return $this->addCommand('linestring', compact('column', 'srid', 'dimensions', 'typmod'));
	}

	/**
	 * Add a multilinestring column on the table
	 *
	 * @param      $column
	 * @param null $srid
	 * @param int  $dimensions
	 * @param bool $typmod
	 * @return \Illuminate\Support\Fluent
	 */
	public function multilinestring( $column, $srid = null, $dimensions = 2, $typmod = true )
	{
		return $this->addCommand( 'multilinestring', compact( 'column', 'srid', 'dimensions', 'typmod' ) );
	}

	/**
 * Add a geometrycollection column on the table
 *
 * @param      $column
 * @param null $srid
 * @param int  $dimensions
 * @param bool $typmod
 * @return \Illuminate\Support\Fluent
 */
	public function geometrycollection( $column, $srid = null, $dimensions = 2, $typmod = true )
	{
		return $this->addCommand( 'geometrycollection', compact( 'column', 'srid', 'dimensions', 'typmod' ) );
	}

}
