<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FreshDatabaseWithSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset {--seed : Run the database seeder} {--seeder= : The specific seeder to run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables and re-migrate (with foreign key checks disabled)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Dropping all tables...');

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Get all table names
        $tables = DB::select('SHOW TABLES');

        if (empty($tables)) {
            $this->info('No tables to drop.');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return Command::SUCCESS;
        }

        $databaseName = DB::getDatabaseName();
        $tableKey = 'Tables_in_' . $databaseName;

        // Drop each table
        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
            $this->line("Dropped table: {$tableName}");
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('All tables dropped successfully.');

        // Run migrations
        $this->info('Running migrations...');
        $this->call('migrate', ['--force' => true]);

        // Run seeder if requested
        if ($this->option('seed') || $this->option('seeder')) {
            $seeder = $this->option('seeder') ?? 'DatabaseSeeder';
            $this->info("Running seeder: {$seeder}");
            $this->call('db:seed', ['--class' => $seeder, '--force' => true]);
        }

        $this->info('Database reset completed successfully!');
        return Command::SUCCESS;
    }
}
