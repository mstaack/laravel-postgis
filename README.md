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
 * Move PostgisModel into a trait rather than subclass.

## Usage

First of all, enable postgis (See note further down)

### Migrations

```PHP
use Illuminate\Database\Migrations\Migration;
use Phaza\LaravelPostgis\Schema\Blueprint;

class CreateTestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tests', function(Blueprint $table)
		{
			$table->increments('id');
			$table->polygon('myPolygon');
			$table->point('myPoint');
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
		Schema::drop('tests');
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

All models which are to be PostGis enabled **must** inherit from *PostgisModel* rather than *Model*.

You must also define an associative array called `$postgisFields` which defines
what attributes/columns on your model are to be considered geometry objects.

```PHP
class TestModel extends PostgisModel {

	protected $postgisFields = [
		'point' => Point::class
	];
}

$testModel = new TestModel();
$testModel->point = new Point(1,2);
$testModel->save();

$testModel2 = TestModel::first();
$testModel2->point instanceof Point // true
```

Available geometry classes:
 
 * Point
 * MultiPoint
 * LineString
 * MultiLineString
 * Polygon
 * MultiPolygon
 * GeometryCollection

## Enabling postgis

A method called enablePostgis() (and disablePostgis()) is included in the Blueprint object.
They work on newer postgres installations, but I recommend enabling postgis manually for now.
