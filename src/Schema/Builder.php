<?php namespace MStaack\LaravelPostgis\Schema;

use Closure;

class Builder extends \Bosnadev\Database\Schema\Builder
{
    /**
     * Create a new command set with a Closure.
     *
     * @param string $table
     * @param Closure $callback
     * @return Blueprint
     */
    public function createBlueprint($table, Closure $callback = null)
    {
        return new Blueprint($table, $callback);
    }

    /**
     * Enable postgis on this database.
     * Will create the extension in the database.
     *
     * @return bool
     */
    public function enablePostgis()
    {
        return $this->connection->statement(
            $this->grammar->compileEnablePostgis()
        );
    }

    /**
     * Enable postgis on this database.
     * Will create the extension in the database if it doesn't already exist.
     *
     * @return bool
     */
    public function enablePostgisIfNotExists()
    {
        return $this->connection->statement(
            $this->grammar->compileEnablePostgisIfNotExists()
        );
    }

    /**
     * Disable postgis on this database.
     * WIll drop the extension in the database.
     *
     * @return bool
     */
    public function disablePostgis()
    {
        return $this->connection->statement(
            $this->grammar->compileDisablePostgis()
        );
    }

    /**
     * Disable postgis on this database.
     * WIll drop the extension in the database if it exists.
     *
     * @return bool
     */
    public function disablePostgisIfExists()
    {
        return $this->connection->statement(
            $this->grammar->compileDisablePostgisIfExists()
        );
    }
}
