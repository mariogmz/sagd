<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Transferencia;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransferenciaController extends Controller
{

    protected $transferencia;

    public function __construct(Transferencia $transferencia)
    {
        $this->transferencia = $transferencia;
        $this->middleware('jwt.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSalidas()
    {
        $empleado = $this->getLoggedInEmpleado();
        if (is_null($empleado)) {
            return response()->json([
                'message' => 'El empleado no se encontro',
                'error' => 'No se pudo encontrar el empleado que realizo esta peticion'
                ], 404);
        } else {
            return $this->transferencia->where('sucursal_origen_id', $empleado->sucursal_id)->get();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexEntradas()
    {
        $empleado = $this->getLoggedInEmpleado();
        if (is_null($empleado)) {
            return response()->json([
                'message' => 'El empleado no se encontro',
                'error' => 'No se pudo encontrar el empleado que realizo esta peticion'
                ], 404);
        } else {
            return $this->transferencia->where('sucursal_destino_id', $empleado->sucursal_id)->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $params = $request->all();
        $this->transferencia = $this->transferencia->fill($params);
        if ($this->transferencia->save()) {
            return response()->json([
                'message' => 'Transferencia pre-guardada exitosamente',
                'transferencia' => $this->transferencia->self(),
                ], 201,
                ['Location' => route('api.v1.transferencias.salidas.ver', $this->transferencia->getId())]);
        } else {
            return response()->json([
                'message' => 'Transferencia no creada',
                'error' => 'La transferencia no pudo ser pre-guardada'
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
        $this->transferencia = $this->transferencia->find($id);
        if ($this->transferencia) {
            return response()->json([
                'message' => 'Transferencia obtenida exitosamente',
                'transferencia' => $this->transferencia->self()
                ], 200);
        } else {
            return response()->json([
                'message' => 'Transferencia no encontrada o no existente',
                'error' => 'La transferencia no pudo ser encontrada. Quizas no existe'
                ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $parameters = $request->all();
        $this->transferencia = $this->transferencia->find($id);
        if( empty($this->transferencia) )
        {
            return response()->json([
                'message' => 'No se pudo realizar la actualizacion de la transferencia',
                'error' => 'Transferencia no encontrada'
            ], 404);
        }
        if( $this->transferencia->update($parameters) )
        {
            return response()->json([
                'message' => 'Transferencia se actualizo correctamente',
                'transferencia' => $this->transferencia->self()
            ], 200);
        } else {
            return response()->json([
                'message' => 'No se pudo realizar la actualizacion de la transferencia',
                'error' => $this->transferencia->errors
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
        $this->transferencia = $this->transferencia->find($id);
        if( empty($this->transferencia) )
        {
            return response()->json([
                'message' => 'No se pudo eliminar la transferencia',
                'error' => 'Transferencia no encontrada'
            ], 404);
        }
        if( $this->transferencia->delete() )
        {
            return response()->json([
                'message' => 'Transferencia eliminada correctamente'
            ], 200);
        } else {
            return response()->json([
                'message' => 'No se pudo eliminar la transferencia',
                'error' => 'El metodo de eliminar no se pudo ejecutar'
            ], 400);
        }
    }

    /**
     * Actualiza la transferencia al estado de Cargado
     *
     * @param int $id
     * @return Response
     */
    public function cargar($id)
    {

    }

    /**
     * Actualiza la transferencia al estado de En Transferencia
     *
     * @param int $id
     * @return Response
     */
    public function transferir($id)
    {

    }
}
