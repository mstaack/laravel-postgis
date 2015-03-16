<?php namespace Phaza\LaravelPostgis\Schema\Grammars;

use Illuminate\Support\Fluent;
use Phaza\LaravelPostgis\Schema\Blueprint;
use Phaza\LaravelPostgres\Schema\Grammars\PostgresGrammar;

class PostgisGrammar extends PostgresGrammar {
	/**
	 * Adds a statement to add a point geometry column
	 *
	 * @param Blueprint $blueprint
	 * @param Fluent    $command
	 * @return string
	 */
	public function compilePoint( Blueprint $blueprint, Fluent $command )
	{
		$command->type = 'POINT';

		return $this->compileGeometry( $blueprint, $command );
	}

	/**
	 * Adds a statement to add a point geometry column
	 *
	 * @param Blueprint $blueprint
	 * @param Fluent    $command
	 * @return string
	 */
	public function compileMultipoint( Blueprint $blueprint, Fluent $command )
	{
		$command->type = 'MULTIPOINT';

		return $this->compileGeometry( $blueprint, $command );
	}

	/**
	 * Adds a statement to add a polygon geometry column
	 *
	 * @param Blueprint $blueprint
	 * @param Fluent    $command
	 * @return string
	 */
	public function compilePolygon( Blueprint $blueprint, Fluent $command )
	{
		$command->type = 'POLYGON';

		return $this->compileGeometry( $blueprint, $command );
	}

	/**
	 * Adds a statement to add a multipolygon geometry column
	 *
	 * @param Blueprint $blueprint
	 * @param Fluent    $command
	 * @return string
	 */
	public function compileMultipolygon( Blueprint $blueprint, Fluent $command )
	{
		$command->type = 'MULTIPOLYGON';

		return $this->compileGeometry( $blueprint, $command );
	}

	/**
	 * Adds a statement to add a linestring geometry column
	 *
	 * @param Blueprint $blueprint
	 * @param Fluent    $command
	 * @return string
	 */
	public function compileLinestring( Blueprint $blueprint, Fluent $command )
	{
		$command->type = 'LINESTRING';

		return $this->compileGeometry( $blueprint, $command );
	}

	/**
	 * Adds a statement to add a multilinestring geometry column
	 *
	 * @param Blueprint $blueprint
	 * @param Fluent    $command
	 * @return string
	 */
	public function compileMultilinestring( Blueprint $blueprint, Fluent $command )
	{
		$command->type = 'MULTILINESTRING';

		return $this->compileGeometry( $blueprint, $command );
	}

	/**
	 * Adds a statement to add a geometrycollection geometry column
	 *
	 * @param Blueprint $blueprint
	 * @param Fluent    $command
	 * @return string
	 */
	public function compileGeometrycollection( Blueprint $blueprint, Fluent $command )
	{
		$command->type = 'GEOMETRYCOLLECTION';

		return $this->compileGeometry( $blueprint, $command );
	}

	/**
	 * Adds a statement to add a geometry column
	 *
	 * @param Blueprint $blueprint
	 * @param Fluent    $command
	 * @return string
	 */
	protected function compileGeometry( Blueprint $blueprint, Fluent $command )
	{

		$dimensions = $command->dimensions ?: 2;
		$typmod     = $command->typmod ? 'true' : 'false';
		$srid       = $command->srid ?: 4236;

		return sprintf( "SELECT AddGeometryColumn('%s', '%s', %d, '%s', %d, %s)",
			$blueprint->getTable(),
			$command->column,
			$srid,
			strtoupper( $command->type ),
			$dimensions,
			$typmod
		);
	}
}
