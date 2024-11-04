<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DownloadProductFilesService
{
    private string $destinationPath;

    public function __construct()
    {
        $this->destinationPath = public_path('imports/');

        if (!File::exists($this->destinationPath)) {
            File::makeDirectory($this->destinationPath, 0755, true);
        }
    }

    public function downloadFiles(): void
    {
        $this->deleteExistingFiles();

        $indexUrl = 'https://challenges.coode.sh/food/data/json/index.txt';
        $fileList = Http::get($indexUrl)->body();
        $files = explode("\n", trim($fileList));

        foreach ($files as $file) {
            $this->downloadFile($file);
        }
    }

    private function deleteExistingFiles(): void
    {
        $existingFiles = File::files($this->destinationPath);
        foreach ($existingFiles as $existingFile) {
            File::delete($existingFile);
            Log::info("File {$existingFile->getFilename()} deleted.");
        }
    }

    private function downloadFile(string $file): void
    {
        $url = "https://challenges.coode.sh/food/data/json/{$file}";
        $response = Http::timeout(60)->get($url);

        if ($response->ok()) {
            $gzFilePath = $this->destinationPath . $file;
            File::put($gzFilePath, $response->body());
            Log::info("File {$file} downloaded successfully.");

            $this->uncompressAndSaveAsJson($gzFilePath);
        } else {
            Log::error("Failed to download file {$file}: {$response->status()} - {$response->body()}");
        }
    }

    private function uncompressAndSaveAsJson(string $gzFilePath): void
    {
        $jsonFilePath = preg_replace('/\.gz$/', '', $gzFilePath);
        $maxProducts = 100;
        $productCount = 0;

        $gzFile = gzopen($gzFilePath, 'rb');
        if ($gzFile === false) {
            Log::error("Failed to open file {$gzFilePath} for decompression.");
            return;
        }

        $jsonFile = fopen($jsonFilePath, 'wb');
        if ($jsonFile === false) {
            Log::error("Failed to create output file {$jsonFilePath}.");
            gzclose($gzFile);
            return;
        }

        fwrite($jsonFile, "[");

        while (!gzeof($gzFile) && $productCount < $maxProducts) {
            $line = gzgets($gzFile);

            $product = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if ($productCount > 0) {
                    fwrite($jsonFile, ",");
                }

                fwrite($jsonFile, json_encode($product));
                $productCount++;
            }
        }

        fwrite($jsonFile, "]");

        gzclose($gzFile);
        fclose($jsonFile);

        if ($productCount > 0) {
            Log::info("File decompressed and limited to 100 products saved as {$jsonFilePath} successfully.");
            File::delete($gzFilePath);
        } else {
            Log::error("Failed to save decompressed products in {$jsonFilePath}.");
        }
    }
}
