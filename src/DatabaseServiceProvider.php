<?php namespace Phaza\LaravelPostgis;

use Illuminate\Database\DatabaseManager;
use Phaza\LaravelPostgis\Connectors\ConnectionFactory;
use Bosnadev\Database\DatabaseServiceProvider as PostgresDatabaseServiceProvider;

/**
 * Class DatabaseServiceProvider
 * @package Phaza\LaravelPostgis
 */
class DatabaseServiceProvider extends PostgresDatabaseServiceProvider
{
    public function boot() {
      // Load the config
      $config_path = __DIR__ . '/../config/postgis.php';
      $this->publishes([$config_path => config_path('postgis.php')], 'postgis');
      $this->mergeConfigFrom($config_path, 'postgis');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // The connection factory is used to create the actual connection instances on
        // the database. We will inject the factory into the manager so that it may
        // make the connections while they are actually needed and not of before.
        $this->app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });

        // The database manager is used to resolve various connections, since multiple
        // connections might be managed. It also implements the connection resolver
        // interface which may be used by other components requiring connections.
        $this->app->singleton('db', function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });
    }
}
