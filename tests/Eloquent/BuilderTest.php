<?php

namespace MStaack\LaravelPostgis\Tests\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Expression;
use Mockery as m;
use MStaack\LaravelPostgis\Eloquent\Builder;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use MStaack\LaravelPostgis\Geometries\LineString;
use MStaack\LaravelPostgis\Geometries\Point;
use MStaack\LaravelPostgis\Geometries\Polygon;
use MStaack\LaravelPostgis\Tests\BaseTestCase;

class BuilderTest extends BaseTestCase
{
    protected $builder;

    /**
     * @var \Mockery\MockInterface $queryBuilder
     */
    protected $queryBuilder;

    protected function setUp(): void
    {
        $this->queryBuilder = m::mock(QueryBuilder::class);
        $this->queryBuilder->makePartial();

        $this->queryBuilder
            ->shouldReceive('from')
            ->andReturn($this->queryBuilder);

        $this->queryBuilder
            ->shouldReceive('take')
            ->with(1)
            ->andReturn($this->queryBuilder);

        $this->queryBuilder
            ->shouldReceive('get')
            ->andReturn([]);

        $this->builder = new Builder($this->queryBuilder);
        $this->builder->setModel(new class extends Model {
            use PostgisTrait;
            protected $postgisFields = [
                'point' => Point::class,
                'linestring' => LineString::class,
                'polygon' => Polygon::class
            ];
        });
    }

    public function testUpdate()
    {
        $this->queryBuilder
            ->shouldReceive('raw')
            ->with("public.ST_GeogFromText('POINT(2 1)')")
            ->andReturn(new Expression("public.ST_GeogFromText('POINT(2 1)')"));

        $this->queryBuilder
            ->shouldReceive('update')
            ->andReturn(1);

        $builder = m::mock(Builder::class, [$this->queryBuilder])->makePartial();
        $builder->shouldAllowMockingProtectedMethods();
        $builder
            ->shouldReceive('addUpdatedAtColumn')
            ->andReturn(['point' => new Point(1, 2)]);

        $builder->update(['point' => new Point(1, 2)]);
    }

    public function testUpdateLinestring()
    {
        $this->queryBuilder
            ->shouldReceive('raw')
            ->with("public.ST_GeogFromText('LINESTRING(0 0, 1 1, 2 2)')")
            ->andReturn(new Expression("public.ST_GeogFromText('LINESTRING(0 0, 1 1, 2 2)')"));

        $this->queryBuilder
            ->shouldReceive('update')
            ->andReturn(1);

        $linestring = new LineString([new Point(0, 0), new Point(1, 1), new Point(2, 2)]);

        $builder = m::mock(Builder::class, [$this->queryBuilder])->makePartial();
        $builder->shouldAllowMockingProtectedMethods();
        $builder
            ->shouldReceive('addUpdatedAtColumn')
            ->andReturn(['linestring' => $linestring]);

        $builder
            ->shouldReceive('asWKT')->with($linestring)->once();

        $builder->update(['linestring' => $linestring]);
    }

    public function testUpdate3d()
    {
        $this->queryBuilder
            ->shouldReceive('raw')
            ->with("public.ST_GeogFromText('POINT Z(2 1 0)')")
            ->andReturn(new Expression("public.ST_GeogFromText('POINT Z(2 1 0)')"));

        $this->queryBuilder
            ->shouldReceive('update')
            ->andReturn(1);

        $builder = m::mock(Builder::class, [$this->queryBuilder])->makePartial();
        $builder->shouldAllowMockingProtectedMethods();
        $builder
            ->shouldReceive('addUpdatedAtColumn')
            ->andReturn(['point' => new Point(1, 2, 0)]);

        $builder->update(['point' => new Point(1, 2, 0)]);
    }

    public function testUpdateLinestring3d()
    {
        $this->queryBuilder
            ->shouldReceive('raw')
            ->with("public.ST_GeogFromText('LINESTRING Z(0 0 0, 1 1 1, 2 2 2)')")
            ->andReturn(new Expression("public.ST_GeogFromText('LINESTRING Z(0 0 0, 1 1 1, 2 2 2)')"));

        $this->queryBuilder
            ->shouldReceive('update')
            ->andReturn(1);

        $linestring = new LineString([new Point(0, 0, 0), new Point(1, 1, 1), new Point(2, 2, 2)]);

        $builder = m::mock(Builder::class, [$this->queryBuilder])->makePartial();
        $builder->shouldAllowMockingProtectedMethods();
        $builder
            ->shouldReceive('addUpdatedAtColumn')
            ->andReturn(['linestring' => $linestring]);

        $builder
            ->shouldReceive('asWKT')->with($linestring)->once();

        $builder->update(['linestring' => $linestring]);
    }
}
