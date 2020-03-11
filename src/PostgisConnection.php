<?php

namespace MStaack\LaravelPostgis;

use Bosnadev\Database\PostgresConnection;
use MStaack\LaravelPostgis\Schema\Grammars\PostgisGrammar;

class PostgisConnection extends PostgresConnection
{
    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);

        // Prevent geography type fields from throwing a 'type not found' error.
        $this->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('geography', 'string');
        $this->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('geometry', 'string');
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new PostgisGrammar());
    }


    public function getSchemaBuilder()
    {
        if ($this->schemaGrammar === null) {
            $this->useDefaultSchemaGrammar();
        }

        return new Schema\Builder($this);
    }
}
