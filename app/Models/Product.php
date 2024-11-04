<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'status',
        'imported_t',
        'updated_t',
        'url',
        'creator',
        'created_t',
        'last_modified_t',
        'product_name',
        'quantity',
        'brands',
        'categories',
        'labels',
        'cities',
        'purchase_places',
        'stores',
        'ingredients_text',
        'traces',
        'serving_size',
        'nutriscore_score',
        'nutriscore_grade',
        'main_category',
        'image_url',
    ];
    public $timestamps = false;
    protected $casts = [
        'ingredients' => 'json',
        'imported_t' => 'datetime',
        'updated_t' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * Verifica se um produto com o código especificado já existe.
     *
     * @param string $code
     * @return bool
     */
    public static function alreadyExists(string $code): bool
    {
        return self::where('code', $code)->exists();
    }

    public static function create(): self
    {
        return new self();
    }

    public function fromImport(object $product): void
    {
        $this->code = isset($product->code) ? trim($product->code, '"') : null;
        $this->status = 'published';
        $this->imported_t = Carbon::now();
        $this->updated_t = Carbon::now();
        $this->url = $product->url ?? null;
        $this->creator = $product->creator ?? null;
        $this->created_t = Carbon::createFromTimestamp($product->created_t ?? time())->toDateTime();
        $this->last_modified_t = Carbon::createFromTimestamp($product->last_modified_t ?? time())->toDateTime();
        $this->product_name = $product->product_name ?? null;
        $this->quantity = $product->quantity ?? null;
        $this->brands = $product->brands ?? null;
        $this->categories = $product->categories ?? null;
        $this->labels = $product->labels ?? null;
        $this->cities = $product->cities ?? null;
        $this->purchase_places = $product->purchase_places ?? null;
        $this->stores = $product->stores ?? null;
        $this->ingredients_text = $product->ingredients_text ?? null;
        $this->traces = $product->traces ?? null;
        $this->serving_size = $product->serving_size ?? null;
        $this->nutriscore_score = $product->nutriscore_score ?? 0;
        $this->nutriscore_grade = $product->nutriscore_grade ?? null;
        $this->main_category = $product->main_category ?? null;
        $this->image_url = $product->image_url ?? null;

        $this->save();
    }
}
