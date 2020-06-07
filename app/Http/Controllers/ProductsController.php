<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{

    public function index($id = null)
    {
        $products = "";
        if ($id) {
            $departments = DB::table('products')
                ->join('departments', 'products.id_department', 'departments.id')
                ->join('categories', 'products.id_category', 'categories.id')
                ->join('brands', 'products.id_brand', 'brands.id')
                ->where('products.id', $id)
                ->get();
        } else {
            $departments = Product::join('departments', 'products.id_department', 'departments.id')
                ->join('categories', 'products.id_category', 'categories.id')
                ->join('brands', 'products.id_brand', 'brands.id')
                ->select('products.*', 'brands.name as brand', 'departments.name as department', 'categories.name as category')
                ->get();
        }

        return response()->json(['success' => true, 'products' => $departments]);
    }

    public function store(ProductRequest $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->id_department = $request->id_department;
        $product->id_category = $request->id_category;
        $product->id_brand = $request->id_brand;
        $product->unit_price = $request->unit_price;
        $product->purchase_price = $request->purchase_price;
        $product->quantity_small_size = $request->quantity_small_size;
        $product->quantity_medium_size = $request->quantity_medium_size;
        $product->quantity_big_size = $request->quantity_big_size;

        try {
            if ($product->save()) {
                return response()->json(['success' => true, 'message' => 'Se ha creado el producto correctamente.'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No se ha creado el producto.'], 413);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
