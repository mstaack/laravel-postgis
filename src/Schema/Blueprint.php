<?php namespace MStaack\LaravelPostgis\Schema;

class Blueprint extends \Bosnadev\Database\Schema\Blueprint
{
    /**
     * Add a point column on the table
     *
     * @param      $column
     * @return \Illuminate\Support\Fluent
     */
    public function point($column, $geomtype = 'GEOGRAPHY', $srid = '4326')
    {
        return $this->addColumn('point', $column, compact('geomtype', 'srid'));
    }

    /**
     * Add a point column on the table
     *
     * @param      $column
     * @return \Illuminate\Support\Fluent
     */
    public function pointz($column, $geomtype = 'GEOGRAPHY', $srid = '4326')
    {
        return $this->addColumn('pointz', $column, compact('geomtype', 'srid'));
    }

    /**
     * Add a multipoint column on the table
     *
     * @param      $column
     * @return \Illuminate\Support\Fluent
     */
    public function multipoint($column, $geomtype = 'GEOGRAPHY', $srid = '4326')
    {
        return $this->addColumn('multipoint', $column, compact('geomtype', 'srid'));
    }

    /**
     * Add a polygon column on the table
     *
     * @param      $column
     * @return \Illuminate\Support\Fluent
     */
    public function polygon($column, $geomtype = 'GEOGRAPHY', $srid = '4326')
    {
        return $this->addColumn('polygon', $column, compact('geomtype', 'srid'));
    }

    /**
     * Add a multipolygon column on the table
     *
     * @param      $column
     * @return \Illuminate\Support\Fluent
     */
    public function multipolygon($column, $geomtype = 'GEOGRAPHY', $srid = '4326')
    {
        return $this->addColumn('multipolygon', $column, compact('geomtype', 'srid'));
    }

    /**
     * Add a multipolygonz column on the table
     *
     * @param $column
     * @return \Illuminate\Support\Fluent
     */
    public function multipolygonz($column, $geomtype = 'GEOGRAPHY', $srid = '4326')
    {
        return $this->addColumn('multipolygonz', $column, compact('geomtype', 'srid'));
    }

    /**
     * Add a linestring column on the table
     *
     * @param      $column
     * @return \Illuminate\Support\Fluent
     */
    public function linestring($column, $geomtype = 'GEOGRAPHY', $srid = '4326')
    {
        return $this->addColumn('linestring', $column, compact('geomtype', 'srid'));
    }

    /**
     * Add a linestringz column on the table
     *
     * @param      $column
     * @return \Illuminate\Support\Fluent
     */
    public function linestringz($column, $geomtype = 'GEOGRAPHY', $srid = '4326')
    {
        return $this->addColumn('linestringz', $column, compact('geomtype', 'srid'));
    }

    /**
     * Add a multilinestring column on the table
     *
     * @param      $column
     * @return \Illuminate\Support\Fluent
     */
    public function multilinestring($column, $geomtype = 'GEOGRAPHY', $srid = '4326')
    {
        return $this->addColumn('multilinestring', $column, compact('geomtype', 'srid'));
    }

    /**
     * Add a geography column on the table
     *
     * @param   string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function geography($column, $geomtype = 'GEOGRAPHY', $srid = '4326')
    {
        return $this->addColumn('geography', $column, compact('geomtype', 'srid'));
    }

    /**
     * Add a geometry column on the table
     *
     * @param   string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function geometry($column, $geomtype = 'GEOGRAPHY', $srid = '4326')
    {
        return $this->addColumn('geometry', $column, compact('geomtype', 'srid'));
    }

    /**
     * Add a geometrycollection column on the table
     *
     * @param      $column
     * @param null $srid
     * @param int $dimensions
     * @param bool $typmod
     * @return \Illuminate\Support\Fluent
     */
    public function geometrycollection($column, $srid = null, $dimensions = 2, $typmod = true)
    {
        return $this->addCommand('geometrycollection', compact('column', 'srid', 'dimensions', 'typmod'));
    }

    /**
     * Enable postgis on this database.
     * Will create the extension in the database.
     *
     * @return \Illuminate\Support\Fluent
     */
    public function enablePostgis()
    {
        return $this->addCommand('enablePostgis');
    }

    /**
     * Enable postgis on this database.
     * Will create the extension in the database if it doesn't already exist.
     *
     * @return \Illuminate\Support\Fluent
     */
    public function enablePostgisIfNotExists()
    {
        return $this->addCommand('enablePostgisIfNotExists');
    }

    /**
     * Disable postgis on this database.
     * WIll drop the extension in the database.
     *
     * @return \Illuminate\Support\Fluent
     */
    public function disablePostgis()
    {
        return $this->addCommand('disablePostgis');
    }

    /**
     * Disable postgis on this database.
     * WIll drop the extension in the database if it exists.
     *
     * @return \Illuminate\Support\Fluent
     */
    public function disablePostgisIfExists()
    {
        return $this->addCommand('disablePostgisIfExists');
    }

}
