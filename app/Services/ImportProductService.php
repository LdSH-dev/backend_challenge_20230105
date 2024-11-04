<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ImportHistory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Finder\SplFileInfo;

class ImportProductService
{
    const MAX_IMPORT_PER_FILE = 100;

    private Collection $importedFiles;
    private int $importCount;

    public function __construct()
    {
        $this->importedFiles = collect(File::allFiles(public_path('imports/')));
        $this->importCount = 0;
    }

    public function importFromOpenFoodAttachment(): void
    {
        $this->importedFiles->each(function (SplFileInfo $file) {
            Log::channel('imports')->info("Checking for new products to import in file {$file->getFilenameWithoutExtension()}.");
            $this->importFileProducts($file);
        });

        Cache::put(env('IMPORT_CRON_LASTRUN_CACHE_KEY'), Carbon::now());
    }

    private function importFileProducts(SplFileInfo $file): void
    {
        $fileContent = collect(json_decode($file->getContents()));
        $this->importCount = 0;
        $errors = [];

        $fileContent->each(function (object $productData) use (&$errors) {
            if ($this->importCount >= self::MAX_IMPORT_PER_FILE) {
                return false;
            }

            try {
                $this->importProduct($productData);
            } catch (\Throwable $e) {
                $errors[] = "Product code {$productData->code}: {$e->getMessage()}";
            }
        });

        ImportHistory::create([
            'imported_at' => Carbon::now(),
            'source_file' => $file->getFilename(),
            'imported_count' => $this->importCount,
            'status' => empty($errors) ? 'success' : 'partial',
            'error_message' => empty($errors) ? null : implode('; ', $errors),
        ]);

        Log::channel('imports')->info("{$this->importCount} new products successfully imported.");
    }

    private function importProduct(object $productData): void
    {
        if (Product::alreadyExists($productData->code)) {
            return;
        }

        try {
            Product::create()->fromImport($productData);
            Log::channel('imports')->info("Product with code {$productData->code} imported successfully.");
            $this->importCount++;
        } catch (\Throwable $e) {
            Log::channel('imports')->error("Failed to import product with code {$productData->code}: {$e->getMessage()}");

            ImportHistory::create([
                'imported_at' => Carbon::now(),
                'source_file' => 'Individual product import',
                'imported_count' => 0,
                'status' => 'failed',
                'error_message' => "Product code {$productData->code}: {$e->getMessage()}",
            ]);

            throw $e;
        }
    }
}
