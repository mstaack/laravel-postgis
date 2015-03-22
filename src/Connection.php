<?php namespace Phaza\LaravelPostgis;

class Connection extends \Bosnadev\Database\Connection {
	public function getSchemaBuilder()
	{
		if (is_null($this->schemaGrammar)) { $this->useDefaultSchemaGrammar(); }

		return new Schema\Builder($this);
	}

}
