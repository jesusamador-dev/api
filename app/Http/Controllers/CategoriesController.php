<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CategorieRequest;
use Exception;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct()
    {
    }

    public function index($status = null)
    {
        $categories = "";
        if ($status) {
            $categories = Category::join('departments', 'categories.id_department', '=', 'departments.id')
                ->select('categories.*', 'departments.name as department')
                ->where('categories.status', $status)
                ->get();
        } else {
            $categories = Category::join('departments', 'categories.id_department', '=', 'departments.id')
                ->select('categories.*', 'departments.name as department')
                ->get();
        }

        return response()->json(['success' => true, 'categories' => $categories]);
    }

    public function getByDepartment($id, $status)
    {
        $categories = "";
        if ($status) {
            $categories = Category::join('departments', 'categories.id_department', '=', 'departments.id')
                ->select('categories.*', 'departments.name as department')
                ->where([
                    ['categories.status', '=', $status],
                    ['categories.id_department', '=', $id],
                ])
                ->get();
        } else {
            $categories = Category::join('departments', 'categories.id_department', '=', 'departments.id')
                ->select('categories.*', 'departments.name as department')
                ->where('categories.id_department', $id)
                ->get();
        }

        return response()->json(['success' => true, 'categories' => $categories]);
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
            $categorie = new Category();
            $categorie->name = $request->name;
            $categorie->status = $request->status;
            $categorie->id_department = $request->department;
            if ($categorie->save()) {
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
            if (Category::destroy($id)) {
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
        $categorie = Category::where('id', $id)->get();
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
            $category = Category::find($id);
            $category->name = $request->name;
            $category->status = $request->status;
            $category->id_department = $request->department;
            if ($category->save()) {
                return response()->json(['success' => true, 'message' => 'Se ha actualzado la categoria correctamente.'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No se ha actualizado la categoria.'], 413);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
