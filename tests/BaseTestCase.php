<?php

namespace MStaack\LaravelPostgis\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }
}
