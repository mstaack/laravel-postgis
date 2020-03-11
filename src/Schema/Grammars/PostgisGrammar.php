<?php namespace MStaack\LaravelPostgis\Schema\Grammars;

use Illuminate\Support\Fluent;
use MStaack\LaravelPostgis\Schema\Blueprint;
use MStaack\LaravelPostgis\Exceptions\UnsupportedGeomtypeException;
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
        return $this->createTypeDefinition($column, 'POINT');
    }

    /**
     * Adds a statement to add a point geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultipoint(Fluent $column)
    {
        return $this->createTypeDefinition($column, 'MULTIPOINT');
    }

    /**
     * Adds a statement to add a polygon geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typePolygon(Fluent $column)
    {
        return $this->createTypeDefinition($column, 'POLYGON');
    }

    /**
     * Adds a statement to add a multipolygon geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultipolygon(Fluent $column)
    {
        return $this->createTypeDefinition($column, 'MULTIPOLYGON');
    }

    /**
     * Adds a statement to add a multipolygonz geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultiPolygonZ(Fluent $column)
    {
        return $this->createTypeDefinition($column, 'MULTIPOLYGONZ');
    }

    /**
     * Adds a statement to add a linestring geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeLinestring(Fluent $column)
    {
        return $this->createTypeDefinition($column, 'LINESTRING');
    }

    /**
     * Adds a statement to add a multilinestring geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultilinestring(Fluent $column)
    {
        return $this->createTypeDefinition($column, 'MULTILINESTRING');
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
     * @return string
     */
    public function compileEnablePostgis()
    {
        return 'CREATE EXTENSION postgis';
    }

    /**
     * Adds a statement to drop the postgis extension
     *
     * @return string
     */
    public function compileDisablePostgis()
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
        $schema = function_exists('config') ? config('postgis.schema') : 'public';

        return sprintf(
                "SELECT %s.AddGeometryColumn('%s', '%s', %d, '%s.%s', %d, %s)",
                $schema,
                $blueprint->getTable(),
                $command->column,
                $srid,
                $schema,
                strtoupper($command->type),
                $dimensions,
                $typmod
        );
    }

    /**
     * Checks if the given $column is a valid geometry type
     *
     * @param \Illuminate\Support\Fluent $column
     * @return boolean
     */
    protected function isValid($column)
    {
        return in_array(strtoupper($column->geomtype), PostgisGrammar::$allowed_geom_types) && is_int((int) $column->srid);
    }

    /**
     * Create definition for geometry types.
     * @param Fluent $column
     * @param string $geometryType
     * @return string
     * @throws UnsupportedGeomtypeException
     */
    private function createTypeDefinition(Fluent $column, $geometryType)
    {
        $schema = function_exists('config') ? config('postgis.schema') : 'public';
        $type = strtoupper($column->geomtype);
        if ($this->isValid($column)) {
            if ($type == 'GEOGRAPHY' && $column->srid != 4326) {
                throw new UnsupportedGeomtypeException('Error with validation of srid! SRID of GEOGRAPHY must be 4326)');
            }
            return $schema . '.' . $type . '(' . $geometryType . ', ' . $column->srid . ')';
        } else {
            throw new UnsupportedGeomtypeException('Error with validation of geom type or srid!');
        }
    }

}
