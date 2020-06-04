<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Requests\CreateDepartmentRequest;
use Exception;
use Illuminate\Http\Request;

class DepartmentsController extends Controller
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
        $departments = "";
        if ($status) {
            $departments = Department::where('status', $status)->orderBy('create_at', 'desc')->get();
        } else {
            $departments = Department::all();
        }

        return response()->json(['success' => true, 'departments' => $departments]);
    }

    /**
     *
     * Se encarga de crear un nuevo departamento
     * @param Request de un departamento
     *
     */
    public function store(CreateDepartmentRequest $request)
    {
        try {
            $department = new Department();
            $department->name = $request->name;
            $department->status = $request->status;
            if ($department->save()) {
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

            if (Department::destroy($id)) {
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
        $department = Department::where('id', $id)->get();
        return response()->json(['success' => true, 'department' => $department]);
    }

    /**
     *
     * Actualiza un departamento
     * @param Request
     * @param id de un departamento
     *
     */
    public function update(CreateDepartmentRequest $request, $id)
    {
        try {
            $department = Department::find($id);
            $department->name = $request->name;
            $department->status = $request->status;
            if ($department->save()) {
                return response()->json(['success' => true, 'message' => 'Se ha actualzado el departamento correctamente.'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No se ha actualizado el departamento.'], 413);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
