Laravel postgis extension
=========================

[![Build Status](https://travis-ci.org/njbarrett/laravel-postgis.svg?branch=master)](https://travis-ci.org/njbarrett/laravel-postgis.svg?branch=master)
[![Code Climate](https://codeclimate.com/github/njbarrett/laravel-postgis/badges/gpa.svg)](https://codeclimate.com/github/njbarrett/laravel-postgis)
[![Coverage Status](https://coveralls.io/repos/github/njbarrett/laravel-postgis/badge.svg?branch=master)](https://coveralls.io/github/njbarrett/laravel-postgis?branch=master)

## Features

 * Work with geometry classes instead of arrays. (`$myModel->myPoint = new Point(1,2)`)
 * Adds helpers in migrations. (`$table->polygon('myColumn')`)

### Future plans

 * Geometry functions on the geometry classes (contains(), equals(), distance(), etc… (HELP!))

## Versions
- Use 2.* for Laravel 5.1.*
- Use 3.* for Laravel 5.2.*
- Use 3.* for Laravel 5.3.*
- Use 3.* for Laravel 5.4.*
- Use 3.* for Laravel 5.5.*

## Installation

    composer require phaza/laravel-postgis

For laravel >=5.5 that's all. This package supports Laravel new [Package Discovery](https://laravel.com/docs/5.5/packages#package-discovery).

If you are using Laravel < 5.5, you also need to add the DatabaseServiceProvider to your `config/app.php` file.

    'Phaza\LaravelPostgis\DatabaseServiceProvider',


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
what attributes/columns on your model are to be considered geometry objects. By default, all attributes are of type `geography`. If you want to use `geometry` with a custom SRID, you have to define an array called `$postgisTypes`. The keys of this assoc array must match the entries in `$postgisFields` (all missing keys default to `geography`), the values are assoc arrays, too. They must have two keys: `geomtype` which is either `geography` or `geometry` and `srid` which is the desired SRID. **Note**: Custom SRID is only supported for `geometry`, not `geography`.

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
        'location',
        'location2',
        'location3',
        'polygon',
        'polygon2'
    ];
    
    protected $postgisTypes = [
        'location' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ],
        'location2' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ],
        'location3' => [
            'geomtype' => 'geometry',
            'srid' => 27700
        ],
        'polygon' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ],
        'polygon2' => [
            'geomtype' => 'geometry',
            'srid' => 27700
        ]
    ]
}

$linestring = new LineString(
    [
        new Point(0, 0),
        new Point(0, 1),
        new Point(1, 1),
        new Point(1, 0),
        new Point(0, 0)
    ]
);

$location1 = new Location();
$location1->name = 'Googleplex';
$location1->address = '1600 Amphitheatre Pkwy Mountain View, CA 94043';
$location1->location = new Point(37.422009, -122.084047);
$location1->location2 = new Point(37.422009, -122.084047);
$location1->location3 = new Point(37.422009, -122.084047);
$location1->polygon = new Polygon([$linestring]);
$location1->polygon2 = new Polygon([$linestring]);
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
