<?php namespace Eloquent;

use BaseTestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Expression;
use Mockery as m;
use Phaza\LaravelPostgis\Eloquent\Builder;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Polygon;

class BuilderTest extends BaseTestCase {
	protected $builder;

	/**
	 * @var \Mockery\MockInterface $queryBuilder
	 */
	protected $queryBuilder;

	protected function setUp()
	{
		$this->queryBuilder = m::mock( QueryBuilder::class );
		$this->queryBuilder->makePartial();

		$this->queryBuilder
			->shouldReceive( 'from' )
			->andReturn( $this->queryBuilder );

		$this->queryBuilder
			->shouldReceive( 'take' )
			->with( 1 )
			->andReturn( $this->queryBuilder );

		$this->queryBuilder
			->shouldReceive( 'get' )
			->andReturn( [ ] );

		$this->builder = new Builder( $this->queryBuilder );
		$this->builder->setModel( new TestBuilderModel() );
	}

	public function testFirst()
	{
		$this->queryBuilder
			->shouldReceive( 'first' )
			->with( [ '*', 'ST_AsText(point) AS point', 'ST_AsText(polygon) AS polygon' ] )
			->andReturn( [ ] );

		$this->queryBuilder
			->shouldReceive( 'raw' )
			->with( 'ST_AsText(point) AS point' )
			->andReturn( new Expression( 'ST_AsText(point) AS point' ) );

		$this->queryBuilder
			->shouldReceive( 'raw' )
			->with( 'ST_AsText(polygon) AS polygon' )
			->andReturn( new Expression( 'ST_AsText(polygon) AS polygon' ) );

		$this->builder->first();
	}

	public function testGet()
	{
		$this->queryBuilder
			->shouldReceive( 'get' )
			->with( [ '*', 'ST_AsText(point) AS point', 'ST_AsText(polygon) AS polygon' ] )
			->andReturn( [ ] );

		$this->queryBuilder
			->shouldReceive( 'raw' )
			->with( 'ST_AsText(point) AS point' )
			->andReturn( new Expression( 'ST_AsText(point) AS point' ) );

		$this->queryBuilder
			->shouldReceive( 'raw' )
			->with( 'ST_AsText(polygon) AS polygon' )
			->andReturn( new Expression( 'ST_AsText(polygon) AS polygon' ) );

		$this->builder->get();
	}

	public function testUpdate()
	{
		$this->queryBuilder
			->shouldReceive( 'raw' )
			->with( "ST_GeogFromText('POINT(2 1)')" )
			->andReturn( new Expression( "ST_GeogFromText('POINT(2 1)')" ) );

		$this->queryBuilder
			->shouldReceive( 'update' )
			->andReturn( 1 );

		$builder = m::mock(Builder::class, [$this->queryBuilder])->makePartial();
		$builder->shouldAllowMockingProtectedMethods();
		$builder
			->shouldReceive( 'addUpdatedAtColumn' )
			->andReturn( [ 'point' => new Point( 1, 2 ) ] );

		$builder->update( [ 'point' => new Point( 1, 2 ) ] );
	}
}

class TestBuilderModel extends Model {
	use PostgisTrait;

	protected $postgisFields = [
		'point'   => Point::class,
		'polygon' => Polygon::class
	];
}
