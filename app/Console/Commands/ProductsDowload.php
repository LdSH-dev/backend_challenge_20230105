<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ImportHistory;
use App\Services\DownloadProductFilesService;
use App\Services\ImportProductService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductsDowload extends Command
{
    protected $signature = 'products:download';
    protected $description = 'Download products from the Open Food Facts API';

    public function handle(DownloadProductFilesService $downloadService)
    {
        echo "Iniciando o processo de download dos arquivos.\n";
        $downloadService->downloadFiles();
    }
}
