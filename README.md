Laravel Wrapper for PostgreSQL's Geo-Extension Postgis
======================================================

![Build Status](https://github.com/mstaack/laravel-postgis/workflows/Test%20Suite/badge.svg)

## Features

 * Work with geometry classes instead of arrays.
```php
$model->myPoint = new Point(1,2);  //lat, long
```

* Adds helpers in migrations.
```php
$table->polygon('myColumn');
```

## Warning
This Package has been moved to a new owner and aims for Laravel 6/7/8/9  and PHP 7 support only soon!

Replace all your references to the new namespace:
```
MStaack\LaravelPostgis
```

Thanks to :
- https://github.com/njbarrett
- https://github.com/phaza
- https://github.com/mirzap

Fluent in Laravel Packages and Postgres/Postgis? Consider contributing! We are looking for anyone that wants to help out!

## Installation
- Use 3.* for Laravel 5

```bash
composer require "mstaack/laravel-postgis:3.*"
```

- Use 5.* for Laravel 6/7/8/9
```bash
composer require mstaack/laravel-postgis
```

For laravel >=5.5 that's all. This package supports Laravel new [Package Discovery](https://laravel.com/docs/5.5/packages#package-discovery).

If you are using Laravel < 5.5, you also need to add the DatabaseServiceProvider to your `config/app.php` file.
```php
'MStaack\LaravelPostgis\DatabaseServiceProvider',
```
## Usage

To start, ensure you have PostGIS enabled in your database - you can do this in a Laravel migration or manually via SQL.

### Enable PostGIS via a Laravel migration

You need to publish the migration to easily enable PostGIS:

```sh
php artisan vendor:publish --provider="MStaack\LaravelPostgis\DatabaseServiceProvider" --tag="migrations"
```

And then you run the migrations:

```sh
php artisan migrate
```

These methods are safe to use and will only enable / disable the PostGIS extension if relevant - they won't cause an error if PostGIS is / isn't already enabled.

If you prefer, you can use the `enablePostgis()` method which will throw an error if PostGIS is already enabled, and the `disablePostgis()` method twhich will throw an error if PostGIS isn't enabled.

### Enable PostGIS manually

Use an SQL client to connect to your database and run the following command:

    CREATE EXTENSION postgis;

To verify that PostGIS is enabled you can run:

    SELECT postgis_full_version();

### Migrations

Now create a model with a migration by running

    php artisan make:model Location

If you don't want a model and just a migration run

    php artisan make:migration create_locations_table

Open the created migrations with your editor.

```PHP
use Illuminate\Database\Migrations\Migration;
use MStaack\LaravelPostgis\Schema\Blueprint;

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
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use MStaack\LaravelPostgis\Geometries\Point;

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

## Publishing config
A configuration file exists for overriding default values. To add to your project, run:

```sh
php artisan vendor:publish --provider="MStaack\LaravelPostgis\DatabaseServiceProvider" --tag="postgis"
```
### Coordinate precision
The precision of stored/displayed coordinated can be customised in the config.


```php
# config/postgis.php
return [
    ...
    'precision' => 6,
];
```
See http://wiki.gis.com/wiki/index.php/Decimal_degrees#Accuracy for more information