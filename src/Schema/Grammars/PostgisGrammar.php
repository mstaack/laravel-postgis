<?php namespace Phaza\LaravelPostgis\Schema\Grammars;

use Illuminate\Support\Fluent;
use Phaza\LaravelPostgis\Schema\Blueprint;
use Bosnadev\Database\Schema\Grammars\PostgresGrammar;

class PostgisGrammar extends PostgresGrammar
{
    /**
     * Adds a statement to add a point geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typePoint(Fluent $column)
    {
        return 'GEOGRAPHY(POINT, 4326)';
    }

    /**
     * Adds a statement to add a point geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultipoint(Fluent $column)
    {
        return 'GEOGRAPHY(MULTIPOINT, 4326)';
    }

    /**
     * Adds a statement to add a polygon geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typePolygon(Fluent $column)
    {
        return 'GEOGRAPHY(POLYGON, 4326)';
    }

    /**
     * Adds a statement to add a multipolygon geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultipolygon(Fluent $column)
    {
        return 'GEOGRAPHY(MULTIPOLYGON, 4326)';
    }

    /**
     * Adds a statement to add a linestring geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeLinestring(Fluent $column)
    {
        return 'GEOGRAPHY(LINESTRING, 4326)';
    }

    /**
     * Adds a statement to add a multilinestring geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultilinestring(Fluent $column)
    {
        return 'GEOGRAPHY(MULTILINESTRING, 4326)';
    }

    /**
     * Adds a statement to add a linestring geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeGeography(Fluent $column)
    {
        return 'GEOGRAPHY';
    }

    /**
     * Adds a statement to add a geometrycollection geometry column
     *
     * @param Blueprint $blueprint
     * @param Fluent $command
     * @return string
     */
    public function compileGeometrycollection(Blueprint $blueprint, Fluent $command)
    {
        $command->type = 'GEOMETRYCOLLECTION';

        return $this->compileGeometry($blueprint, $command);
    }

    /**
     * Adds a statement to create the postgis extension
     *
     * @param Blueprint $blueprint
     * @param Fluent $command
     * @return string
     */
    public function compileEnablePostgis(Blueprint $blueprint, Fluent $command)
    {
        return 'CREATE EXTENSION postgis';
    }

    /**
     * Adds a statement to drop the postgis extension
     *
     * @param Blueprint $blueprint
     * @param Fluent $command
     * @return string
     */
    public function compileDisablePostgis(Blueprint $blueprint, Fluent $command)
    {
        return 'DROP EXTENSION postgis';
    }

    /**
     * Adds a statement to add a geometry column
     *
     * @param Blueprint $blueprint
     * @param Fluent $command
     * @return string
     */
    protected function compileGeometry(Blueprint $blueprint, Fluent $command)
    {

        $dimensions = $command->dimensions ?: 2;
        $typmod = $command->typmod ? 'true' : 'false';
        $srid = $command->srid ?: 4326;

        return sprintf(
            "SELECT AddGeometryColumn('%s', '%s', %d, '%s', %d, %s)",
            $blueprint->getTable(),
            $command->column,
            $srid,
            strtoupper($command->type),
            $dimensions,
            $typmod
        );
    }
}
