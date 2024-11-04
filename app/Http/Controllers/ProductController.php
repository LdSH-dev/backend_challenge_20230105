<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // GET /products: List all products (with pagination)
    public function index(Request $request)
    {
        $page = $request->header('X-Page', 1);
        $perPage = 10; // Número de produtos por página

        $products = Product::paginate($perPage, ['*'], 'page', $page);

        return response()->json($products);
    }



    // GET /products/:code: Get details of a single product
    public function show($code)
    {
        $product = Product::where('code', $code)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function update(Request $request, $code)
    {
        $product = Product::where('code', $code)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'created_t' => 'sometimes|nullable|integer',
            'last_modified_t' => 'sometimes|nullable|integer',
            'status' => 'sometimes|in:published,trash,draft',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (isset($request->created_t)) {
            $request->merge([
                'created_t' => Carbon::createFromTimestamp($request->created_t)->toDateTimeString(),
            ]);
        }

        if (isset($request->last_modified_t)) {
            $request->merge([
                'last_modified_t' => Carbon::createFromTimestamp($request->last_modified_t)->toDateTimeString(),
            ]);
        }

        $product->update($request->all());

        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }



    // DELETE /products/:code: Mark product as "trash"
    public function destroy($code)
    {
        $product = Product::where('code', $code)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $product->status = 'trash';
        $product->save();

        return response()->json(['message' => 'Product moved to trash']);
    }

    // POST /products: Create a new product
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:products,code|max:255',
            'product_name' => 'required|string|max:255',
            'created_t' => 'sometimes|nullable|integer',
            'last_modified_t' => 'sometimes|nullable|integer',
            'status' => 'sometimes|in:published,trash,draft',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = new Product();
        $product->fromImport((object) $request->all());

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

}
