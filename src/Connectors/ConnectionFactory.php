<?php namespace Phaza\LaravelPostgis\Connectors;

use PDO;
use Phaza\LaravelPostgis\PostgisConnection;

class ConnectionFactory extends \Bosnadev\Database\Connectors\ConnectionFactory
{
    /**
     * @param string $driver
     * @param PDO $connection
     * @param string $database
     * @param string $prefix
     * @param array $config
     * @return mixed|PostgisConnection
     */
    protected function createConnection($driver, PDO $connection, $database, $prefix = '', array $config = [])
    {
        if ($this->container->bound($key = "db.connection.{$driver}")) {
            return $this->container->make($key, [$connection, $database, $prefix, $config]);
        }

        if ($driver === 'pgsql') {
            return new PostgisConnection($connection, $database, $prefix, $config);
        }

        return parent::createConnection($driver, $connection, $database, $prefix, $config);
    }
}
