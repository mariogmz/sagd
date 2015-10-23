<?php

namespace App\Http\Controllers\Api\V1;

use App\Empleado;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    protected $empleado;

    public function __construct(Empleado $empleado)
    {
        $this->empleado = $empleado;
        // $this->middleware('jwt.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->empleado->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $this->empleado->fill($params);
        if ($this->empleado->save()) {
            return response()->json([
                'message' => 'Empleado creado exitosamente',
                'empleado' => $this->empleado->self()
            ], 201, [
                'Location' => route('api.v1.empleado.show', $this->empleado->getId())
            ]);
        } else {
            return response()->json([
                'message' => 'Empleado no creado',
                'error' => $this->empleado->errors
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->empleado = $this->empleado->find($id);
        if ($this->empleado) {
            return response()->json([
                'message' => 'Empleado obtenido exitosamente',
                'empleado' => $this->empleado->self()
            ], 200);
        } else {
            return response()->json([
                'message' => 'Empleado no encontrado o no existente',
                'error' => 'No encontrado'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $params = $request->all();
        $this->empleado = $this->empleado->find($id);
        if (empty($this->empleado)) {
            return response()->json([
                'message' => 'No se pudo realizar la actualizacion del empleado',
                'error' => 'Empleado no encontrado'
            ], 404);
        } elseif ($this->empleado->update($params)) {
            return response()->json([
                'message' => 'Empleado se actualizo correctamente'
            ], 200);
        } else {
            return response()->json([
                'message' => 'No se pudo realizar la actualizacion del empleado',
                'error' => $this->empleado->errors
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->empleado = $this->empleado->find($id);
        if (empty($this->empleado)) {
            return response()->json([
                'message' => 'No se pudo eliminar el empleado',
                'error' => 'Empleado no encontrado'
            ], 404);
        } elseif ($this->empleado->delete()) {
            return response()->json([
                'message' => 'Empleado eliminado correctamente'
            ], 200);
        } else {
            return response()->json([
                'message' => 'No se pudo eliminar el empleado',
                'error' => $this->empleado->errors
            ], 400);
        }
    }
}
