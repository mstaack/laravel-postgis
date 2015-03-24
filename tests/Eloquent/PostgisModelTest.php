<?php

use Illuminate\Database\Eloquent\Model;
use Mockery as m;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\PostgisConnection;

class PostgisModelTest extends BaseTestCase {

	/**
	 * @var TestModel
	 */
	protected $model;

	/**
	 * @var array
	 */
	protected $queries;

	public function setUp()
	{
		$this->model   = new TestModel();
		$this->queries = &$this->model->getConnection()->getPdo()->queries;
	}

	public function tearDown()
	{
		$this->model->getConnection()->getPdo()->resetQueries();
	}

	public function testInsertPointHasCorrectSql()
	{
		$this->model->point = new Point( 1, 2 );
		$this->model->save();

		$this->assertContains( "ST_GeomFromText('POINT(2 1)', 4326)", $this->queries[0] );
	}

	public function testUpdatePointHasCorrectSql()
	{
		$this->model->exists = true;
		$this->model->point  = new Point( 2, 4 );
		$this->model->save();

		$this->assertContains( "ST_GeomFromText('POINT(4 2)', 4326)", $this->queries[0] );
	}

	public function testFetchPointHasCorrectSql()
	{
		TestModel::find( 2 );

		$this->assertContains( "AsText(point) AS point", $this->queries[0] );
	}

	public function testFindModelTypecastsGeometry() {
		$model = TestModel::find( 1 );

		$this->assertInstanceOf( Point::class, $model->point );
		$this->assertEquals( 1, $model->point->getLng() );
		$this->assertEquals( 2, $model->point->getLat() );
	}

	public function testRelatedModelHasCorrectSql()
	{
		$this->model->exists = true;
		$this->model->id     = 1;
		$this->model->testrelatedmodels;

		$this->assertContains( 'AsText(point) AS point', $this->queries[0] );
		$this->assertContains( 'test_related_models', $this->queries[0] );
	}
}

class TestModel extends Model {
	use PostgisTrait;

	protected $postgisFields = [
		'point' => Point::class
	];


	public static $pdo;

	public static function resolveConnection( $connection = null )
	{
		if( is_null( static::$pdo ) ) {
			static::$pdo = m::mock( 'TestPDO' )->makePartial();
		}

		return new PostgisConnection( static::$pdo );
	}

	public function testrelatedmodels()
	{
		return $this->hasMany( TestRelatedModel::class );
	}

}

class TestRelatedModel extends TestModel {
	public function testmodel()
	{
		return $this->belongsTo( TestModel::class );
	}
}

class TestPDO extends PDO {

	public $queries = [ ];
	public $counter = 1;

	public function prepare( $statement, $driver_options = NULL )
	{
		$this->queries[] = $statement;

		$stmt = m::mock( 'PDOStatement' );
		$stmt->shouldReceive( 'execute' );
		$stmt->shouldReceive( 'fetchAll' )->andReturn( [ ['id' => 1, 'point' => 'POINT(1 2)'] ] );
		$stmt->shouldReceive( 'rowCount' )->andReturn( 1 );

		return $stmt;
	}

	public function lastInsertId( $name = null )
	{
		return $this->counter++;;
	}

	public function resetQueries()
	{
		$this->queries = [ ];
	}

}
