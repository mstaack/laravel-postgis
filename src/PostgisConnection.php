<?php namespace Phaza\LaravelPostgis;

class PostgisConnection extends \Bosnadev\Database\PostgresConnection
{
    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new Schema\Grammars\PostgisGrammar);
    }


    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new Schema\Builder($this);
    }
}
