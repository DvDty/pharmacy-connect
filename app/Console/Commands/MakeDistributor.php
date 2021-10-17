<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use Throwable;

class MakeDistributor extends Command
{
    protected $signature = 'make:distributor {name}';

    public function handle(): int
    {
        try {
            $this->addDistributorConstant();
            $this->info('Constant added successfully.');

            $this->makeAddDistributorMigration();
            $this->info('Add distributor migration file created successfully.');

            $this->makeProductsMigrationFile();
            $this->info('Products migration file created successfully.');

            $this->makeModel();
            $this->info('Model created successfully.');

            Artisan::call('migrate');
        } catch (Throwable $throwable) {
            $this->error($throwable->getMessage());

            return 1;
        }

        return 0;
    }

    private function addDistributorConstant(): void
    {
        $filePath = app_path('Services/Distributor.php');
        $fileContent = Str::of(file_get_contents($filePath));

        $snakeCaseName = Str::snake($this->argument('name'));
        $snakeCaseNameUppercase = Str::upper($snakeCaseName);

        $constant = "public const $snakeCaseNameUppercase = '$snakeCaseName';";

        $fileContent = $fileContent->replace('}', '    ' . $constant . PHP_EOL . '}');

        file_put_contents($filePath, $fileContent);
    }

    /**
     * @throws Exception
     */
    private function makeProductsMigrationFile(): void
    {
        $distributorName = Str::of($this->argument('name'));

        $migrationName = 'create_' . $distributorName->snake() . '_products_table';

        Artisan::call('make:migration', ['name' => $migrationName]);

        if (!$this->doesMigrationExists($migrationName)) {
            throw new Exception('Migration not created.');
        }
    }

    private function makeModel(): void
    {
        $distributorName = Str::studly($this->argument('name'));

        $distributorName .= 'Product';

        Artisan::call('make:model', ['name' => $distributorName]);
    }

    /**
     * @throws Exception
     */
    private function makeAddDistributorMigration()
    {
        $distributorName = Str::of($this->argument('name'));

        $migrationName = 'add_' . $distributorName->snake() . '_to_distributors';

        Artisan::call('make:migration', ['name' => $migrationName]);

        if (!$this->doesMigrationExists($migrationName)) {
            throw new Exception('Migration not created.');
        }

        $migrationFile = $this->getLatestMigrationFile();

        $migrationClass = Str::of($migrationFile->getContents())
            ->match('/class ([A-Za-z]+)/');

        $stubPath = base_path('stubs/migration.add.distributor.stub');

        $migrationFile->openFile('a')
            ->fwrite(
                Str::of(file_get_contents($stubPath))
                    ->replace('{{ class }}', $migrationClass)
                    ->replace(
                        '{{ distributor_name }}',
                        $distributorName
                            ->snake()
                            ->replace('_', ' ')
                            ->title(),
                    )
                    ->replace('{{ products_class }}', $distributorName->studly() . 'Product')
            );
    }

    private function doesMigrationExists(string $migrationName): bool
    {
        return Str::of($this->getLatestMigrationFile()->getFilename())
            ->containsAll([
                Carbon::now()->format('Y-m-d'),
                $migrationName,
            ]);
    }

    private function getLatestMigrationFile(): SplFileInfo
    {
        return collect(File::files(database_path('migrations')))->last();
    }
}
