<?php

namespace App\Http\Controllers;

use App\Categorie;
use App\Http\Requests\CategorieRequest;
use Exception;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct()
    {
    }

    /**
     *
     * Se encarga de crear una nueva categoria
     * @param Request de una categoria
     *
     */
    public function store(Request $request)
    {
        try {
            $department = new Categorie();
            $department->name = $request->name;
            $department->status = $request->status;
            if ($department->save()) {
                return response()->json(['success' => true, 'message' => 'Se ha creado la categoria correctamente.'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No se ha creado la categoria.'], 413);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     *
     * Elimina una categoria
     * @param id de una categoria
     *
     */
    public function destroy($id)
    {
        try {
            $categorie = Categorie::find($id);
            $categorie->status = 2;
            if ($categorie->save()) {
                return response()->json(['success' => true, 'message' => 'Se ha eliminado la categoria correctamente.'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No se ha eliminado la categoria.'], 413);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     *
     * Retorna la información de una categoria
     * @param id de una categoria
     * @return JSON
     *
     */
    public function edit($id)
    {
        $categorie = Categorie::where('id', $id)->get();
        return response()->json(['success' => true, 'categorie' => $categorie]);
    }

    /**
     *
     * Actualiza una categoria
     * @param Request
     * @param id de una categoria
     *
     */
    public function update(CategorieRequest $request, $id)
    {
        try {
            $categorie = Categorie::find($id);
            $categorie->name = $request->name;
            $categorie->status = $request->status;
            $categorie->department = $request->department;
            if ($categorie->save()) {
                return response()->json(['success' => true, 'message' => 'Se ha actualzado la categoria correctamente.'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No se ha actualizado la categoria.'], 413);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
