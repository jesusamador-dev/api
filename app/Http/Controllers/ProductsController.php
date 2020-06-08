<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Product;
use Exception;
use Cloudder;
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
        $code = $this->getCodeProduct();
        $product->code = $code;
        $product->description = $request->description;
        $product->id_department = $request->department;
        $product->id_category = $request->category;
        $product->id_brand = $request->brand;
        $product->unit_price = $request->unit_price;
        $product->purchase_price = $request->purchase_price;
        $product->quantity_small_size = $request->quantity_small_size;
        $product->quantity_medium_size = $request->quantity_medium_size;
        $product->quantity_big_size = $request->quantity_big_size;

        try {
            if ($product->save()) {
                $this->uploadImages($request->file('files'), $code);
                return response()->json(['success' => true, 'message' => 'Se ha creado el producto correctamente.'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No se ha creado el producto.'], 413);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function uploadImages($images, $code)
    {

        $i = 1;
        foreach ($images as $image) {
            $path = 'images_products/' . $code . "/";
            $public_id = $code . '_' . $i;
            $path_id = $path . $public_id;
            // $fileName = $code . '_' . $i . $image->get;
            Cloudder::upload($image, $path_id, array(
                "public_id" => $path_id
            ));
            $i++;
        }
    }

    public function getCodeProduct()
    {
        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codeUnique = false;
        $code = '';
        // do {
        $code = substr(str_shuffle($permitted_chars), 0, 10);
        $products = Product::where('code', $code)->get();
        echo json_encode($products);
        // var_dump($products);
        //     if ($products) {
        //         $codeUnique = false;
        //     } else {
        //         $codeUnique = true;
        //     }
        // } while ($codeUnique != true);

        // return $code;
    }
}
