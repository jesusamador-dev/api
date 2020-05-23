<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;
use Exception;
use App\Http\Requests\BrandRequest;

class BrandsController extends Controller
{
    public function __construct()
    {
    }

    /**
     *
     * Regresa un json con los departamentos (activos inactivos o todos)
     * @return JSONResponse
     *
     */
    public function index($status = null)
    {
        $brands = "";
        if ($status) {
            $brands = Brand::where('status', $status)->get();
        } else {
            $brands = Brand::all();
        }

        return response()->json(['success' => true, 'brands' => $brands]);
    }

    /**
     *
     * Se encarga de crear un nuevo departamento
     * @param Request de un departamento
     *
     */
    public function store(BrandRequest $request)
    {
        try {
            $brand = new Brand();

            $brand->name = $request->name;
            $brand->status = $request->status;
            if ($brand->save()) {
                return response()->json(['success' => true, 'message' => 'Se ha creado el departamento correctamente.'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No se ha creado el departamento.'], 413);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     *
     * Elimina un departamento
     * @param id de un departamneto
     *
     */
    public function destroy($id)
    {
        try {
            $brand = Brand::find($id);
            $brand->status = 2;
            if ($brand->save()) {
                return response()->json(['success' => true, 'message' => 'Se ha eliminado el departamento correctamente.'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No se ha eliminado el departamento.'], 413);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     *
     * Retorna la información de un departamento
     * @param id de un departamento
     * @return JSON
     *
     */
    public function edit($id)
    {
        $brand = Brand::where('id', $id)->get();
        return response()->json(['success' => true, 'brand' => $brand]);
    }

    /**
     *
     * Actualiza un departamento
     * @param Request
     * @param id de un departamento
     *
     */
    public function update(BrandRequest $request, $id)
    {
        try {
            $brand = Brand::find($id);
            $brand->name = $request->name;
            $brand->status = $request->status;
            if ($brand->save()) {
                return response()->json(['success' => true, 'message' => 'Se ha actualzado el departamento correctamente.'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No se ha actualizado el departamento.'], 413);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
