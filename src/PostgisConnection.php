<?php namespace Phaza\LaravelPostgis;

class PostgisConnection extends Connection {
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
