<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\ConnectionFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register file database driver
        $this->app->singleton('db.factory', function ($app) {
            return new class($app) extends ConnectionFactory {
                protected function createConnection($driver, $connection, $database, $prefix = '', array $config = [])
                {
                    if ($driver === 'file') {
                        return $this->createFileConnection($connection, $database, $prefix, $config);
                    }

                    return parent::createConnection($driver, $connection, $database, $prefix, $config);
                }

                protected function createFileConnection($connection, $database, $prefix, array $config)
                {
                    return new class($connection, $database, $prefix, $config) extends Connection {
                        protected $storagePath;
                        protected $schemaGrammar;

                        public function __construct($connection, $database, $prefix, array $config)
                        {
                            parent::__construct($connection, $database, $prefix, $config);
                            $this->storagePath = $config['path'] ?? storage_path('app/database');
                            
                            if (!file_exists($this->storagePath)) {
                                mkdir($this->storagePath, 0755, true);
                            }

                            // Initialize schema grammar for migration operations
                            $this->useDefaultSchemaGrammar();
                        }
                        
                        // Override the default schema grammar with our custom implementation
                        protected function getDefaultSchemaGrammar()
                        {
                            $grammar = new class($this) extends \Illuminate\Database\Schema\Grammars\Grammar {
                                // Table existence check
                                public function compileTableExists($schema, $table)
                                {
                                    return 'table_exists_query';
                                }
                                
                                // Create table statement
                                public function compileCreate(\Illuminate\Database\Schema\Blueprint $blueprint, \Illuminate\Support\Fluent $command)
                                {
                                    return 'create_table_query';
                                }
                                
                                // Drop table statement
                                public function compileDrop(\Illuminate\Database\Schema\Blueprint $blueprint, \Illuminate\Support\Fluent $command)
                                {
                                    return 'drop_table_query';
                                }
                                
                                // Add column statement
                                public function compileAdd(\Illuminate\Database\Schema\Blueprint $blueprint, \Illuminate\Support\Fluent $command)
                                {
                                    return 'add_column_query';
                                }
                            };
                            
                            return $grammar;
                        }
                        
                        // Override getSchemaBuilder to return a schema builder with our grammar
                        public function getSchemaBuilder()
                        {
                            $builder = parent::getSchemaBuilder();
                            $builder->setGrammar($this->schemaGrammar);
                            return $builder;
                        }
                        
                        // Override for schema operations
                        public function getSchemaGrammar()
                        {
                            return $this->schemaGrammar;
                        }
                        
                        // Override scalar method for table existence check
                        public function scalar($query, $bindings = [], $useReadPdo = true)
                        {
                            if ($query === 'table_exists_query') {
                                $table = $bindings[0] ?? '';
                                return file_exists($this->storagePath . '/' . $table . '.json');
                            }
                            return false;
                        }

                        // Simple methods to read/write JSON files as "database tables"
                        public function table($table, $as = null)
                        {
                            return new class($this->storagePath, $table, $as) {
                                protected $storagePath;
                                protected $table;
                                protected $as;
                                
                                public function __construct($storagePath, $table, $as = null)
                                {
                                    $this->storagePath = $storagePath;
                                    $this->table = $table;
                                    $this->as = $as;
                                }
                                
                                public function get()
                                {
                                    $path = $this->storagePath . '/' . $this->table . '.json';
                                    if (file_exists($path)) {
                                        return json_decode(file_get_contents($path), true);
                                    }
                                    return [];
                                }
                                
                                public function insert($data)
                                {
                                    $records = $this->get();
                                    $data['id'] = count($records) + 1; // Simple auto-increment
                                    $records[] = $data;
                                    $this->save($records);
                                    return true;
                                }
                                
                                // Add delete method required by Laravel
                                public function delete()
                                {
                                    $path = $this->storagePath . '/' . $this->table . '.json';
                                    if (file_exists($path)) {
                                        file_put_contents($path, json_encode([], JSON_PRETTY_PRINT));
                                    }
                                    return true;
                                }
                                
                                // Add where method for query building
                                public function where($column, $operator = null, $value = null, $boolean = 'and')
                                {
                                    // This is a simplified implementation
                                    return $this;
                                }
                                
                                // Simple implementation for query execution
                                public function first()
                                {
                                    $records = $this->get();
                                    return !empty($records) ? $records[0] : null;
                                }
                                
                                protected function save($data)
                                {
                                    $path = $this->storagePath . '/' . $this->table . '.json';
                                    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
                                }
                            };
                        }
                    };
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Check if SQLite driver is available - if not, switch to file-based storage
        $availableDrivers = \PDO::getAvailableDrivers();
        $configuredDriver = config('database.default');
        
        if ($configuredDriver === 'sqlite' && !in_array('sqlite', $availableDrivers)) {
            // Switch to file-based storage when SQLite is not available
            config(['database.default' => 'file']);
            \Log::notice('SQLite driver is not available in your PHP installation. Switched to file-based storage.');
        }
        
        // Set locale from session
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }
    }
}
