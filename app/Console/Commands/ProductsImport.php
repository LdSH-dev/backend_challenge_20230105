<?php

namespace App\Console\Commands;

use App\Services\ImportProductService;
use Illuminate\Console\Command;

class ProductsImport extends Command
{
    protected $signature = 'products:imports';
    protected $description = 'Import products from the Open Food Facts API';

    public function handle(ImportProductService $service)
    {
        echo "Iniciando o processo de importação de produtos.\n";
        $service->importFromOpenFoodAttachment();
    }
}
