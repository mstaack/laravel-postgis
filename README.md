Laravel postgis extension
=========================

[![Build Status](https://travis-ci.org/phaza/laravel-postgis.svg?branch=master)](https://travis-ci.org/phaza/laravel-postgis)
[![Code Climate](https://codeclimate.com/github/phaza/laravel-postgis/badges/gpa.svg)](https://codeclimate.com/github/phaza/laravel-postgis)
[![Coverage Status](https://coveralls.io/repos/phaza/laravel-postgis/badge.svg)](https://coveralls.io/r/phaza/laravel-postgis)

## Features

 * Work with geometry classes instead of arrays. (`$myModel->myPoint = new Point(1,2)`)
 * Adds helpers in migrations. (`$table->polygon('myColumn')`)
 
### Future plans
 
 * Geometry functions on the geometry classes (contains(), equals(), distance(), etc… (HELP!))

## Versions
Use 2.* for Laravel 5.1.*  
Use 3.* for Laravel 5.2.*

## Installation

    composer require phaza/laravel-postgis 

Next add the DatabaseServiceProvider to your `config/app.php` file.

    'Phaza\LaravelPostgis\DatabaseServiceProvider',

That's all.

## Usage

First of all, make sure to enable postgis.

    CREATE EXTENSION postgis;

To verify that postgis is enabled

    SELECT postgis_full_version();

### Migrations

Now create a model with a migration by running

    php artisan make:model Location

If you don't want a model and just a migration run

    php artisan make:migration create_locations_table

Open the created migrations with your editor.

```PHP
use Illuminate\Database\Migrations\Migration;
use Phaza\LaravelPostgis\Schema\Blueprint;

class CreateLocationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('address')->unique();
            $table->point('location'); // GEOGRAPHY POINT column with SRID of 4326 (these are the default values).
            $table->point('location2', 'GEOGRAPHY', 4326); // GEOGRAPHY POINT column with SRID of 4326 with optional parameters.
            $table->point('location3', 'GEOMETRY', 27700); // GEOMETRY column with SRID of 27700.
            $table->polygon('polygon'); // GEOGRAPHY POLYGON column with SRID of 4326.
            $table->polygon('polygon2', 'GEOMETRY', 27700); // GEOMETRY POLYGON column with SRID of 27700.
            $table->polygon('polygon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('locations');
    }

}
```

Available blueprint geometries:

 * point
 * multipoint
 * linestring
 * multilinestring
 * polygon
 * multipolygon
 * geometrycollection

other methods:

 * enablePostgis
 * disablePostgis

### Models

All models which are to be PostGis enabled **must** use the *PostgisTrait*.

You must also define an array called `$postgisFields` which defines
what attributes/columns on your model are to be considered geometry objects.

```PHP
use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;

class Location extends Model
{
    use PostgisTrait;

    protected $fillable = [
        'name',
        'address'
    ];

    protected $postgisFields = [
        Point::class,
        Polygon::class,
    ];

}

$location1 = new Location();
$location1->name = 'Googleplex';
$location1->address = '1600 Amphitheatre Pkwy Mountain View, CA 94043';
$location1->location = new Point(37.422009, -122.084047);
$location1->save();

$location2 = Location::first();
$location2->location instanceof Point // true
```

Available geometry classes:
 
 * Point
 * MultiPoint
 * LineString
 * MultiLineString
 * Polygon
 * MultiPolygon
 * GeometryCollection
