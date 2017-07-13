<?php namespace Phaza\LaravelPostgis\Schema\Grammars;

use Illuminate\Support\Fluent;
use Phaza\LaravelPostgis\Schema\Blueprint;
use Phaza\LaravelPostgis\Exceptions\UnsupportedGeomtypeException;
use Bosnadev\Database\Schema\Grammars\PostgresGrammar;

class PostgisGrammar extends PostgresGrammar
{

    public static $allowed_geom_types = ['GEOGRAPHY', 'GEOMETRY'];

    /**
     * Adds a statement to add a point geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typePoint(Fluent $column)
    {
        if ((in_array(strtoupper($column->geomtype), PostgisGrammar::$allowed_geom_types)) && (is_int((int) $column->srid))) {
            return strtoupper($column->geomtype) . '(POINT, ' . $column->srid . ')';
        } else {
            throw new UnsupportedGeomtypeException('Error with validation of geom type or srid! (If geom type is GEOGRAPHY then the SRID must be 4326)');
        }
    }

    /**
     * Adds a statement to add a point geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultipoint(Fluent $column)
    {
        if ((in_array(strtoupper($column->geomtype), PostgisGrammar::$allowed_geom_types)) && (is_int((int) $column->srid))) {
            return strtoupper($column->geomtype) . '(MULTIPOINT, ' . $column->srid . ')';
        } else {
            throw new UnsupportedGeomtypeException('Error with validation of geom type or srid! (If geom type is GEOGRAPHY then the SRID must be 4326)');
        }
    }

    /**
     * Adds a statement to add a polygon geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typePolygon(Fluent $column)
    {
        if ((in_array(strtoupper($column->geomtype), PostgisGrammar::$allowed_geom_types)) && (is_int((int) $column->srid))) {
            return strtoupper($column->geomtype) . '(POLYGON, ' . $column->srid . ')';
        } else {
            throw new UnsupportedGeomtypeException('Error with validation of geom type or srid! (If geom type is GEOGRAPHY then the SRID must be 4326)');
        }
    }

    /**
     * Adds a statement to add a multipolygon geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultipolygon(Fluent $column)
    {
        if ((in_array(strtoupper($column->geomtype), PostgisGrammar::$allowed_geom_types)) && (is_int((int) $column->srid))) {
            return strtoupper($column->geomtype) . '(MULTIPOLYGON, ' . $column->srid . ')';
        } else {
            throw new UnsupportedGeomtypeException('Error with validation of geom type or srid! (If geom type is GEOGRAPHY then the SRID must be 4326)');
        }
    }

    /**
     * Adds a statement to add a linestring geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeLinestring(Fluent $column)
    {
        if ((in_array(strtoupper($column->geomtype), PostgisGrammar::$allowed_geom_types)) && (is_int((int) $column->srid))) {
            return strtoupper($column->geomtype) . '(LINESTRING, ' . $column->srid . ')';
        } else {
            throw new UnsupportedGeomtypeException('Error with validation of geom type or srid! (If geom type is GEOGRAPHY then the SRID must be 4326)');
        }
    }

    /**
     * Adds a statement to add a multilinestring geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultilinestring(Fluent $column)
    {
        if ((in_array(strtoupper($column->geomtype), PostgisGrammar::$allowed_geom_types)) && (is_int((int) $column->srid))) {
            return strtoupper($column->geomtype) . '(MULTILINESTRING, ' . $column->srid . ')';
        } else {
            throw new UnsupportedGeomtypeException('Error with validation of geom type or srid! (If geom type is GEOGRAPHY then the SRID must be 4326)');
        }
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
     * Adds a statement to add a geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeGeometry(Fluent $column)
    {
        return 'GEOMETRY';
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
