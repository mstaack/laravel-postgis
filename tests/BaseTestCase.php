<?php

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }
}
