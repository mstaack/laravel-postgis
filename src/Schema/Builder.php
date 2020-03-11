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
     * Enable foreign key constraints.
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
     * Disable foreign key constraints.
     *
     * @return bool
     */
    public function disablePostgis()
    {
        return $this->connection->statement(
            $this->grammar->compileDisablePostgis()
        );
    }
}
