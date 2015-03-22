<?php namespace Phaza\LaravelPostgis;

use Bosnadev\Database\PostgresConnection;

class PostgisConnection extends PostgresConnection {
	/**
	 * Get the default schema grammar instance.
	 *
	 * @return \Illuminate\Database\Grammar
	 */
	protected function getDefaultSchemaGrammar()
	{
		return $this->withTablePrefix(new Schema\Grammars\PostgisGrammar);
	}
}
