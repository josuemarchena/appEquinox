<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            //listar
            //$pedidos = Pedido::with(['provincia', 'personal', 'detalleOrdenes'])->orderBy('id', "asc")->get();
            $pedidos = Pedido::with(['provincia', 'personal', 'productos'])->orderBy('id', "asc")->get();
            $response = $pedidos;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 422);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        //
        try {
            //listar
            $pedidos = Pedido::with(['provincia', 'personal', 'productos'])->orderBy('id', "asc")->get();
            $response = $pedidos;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 422);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //
        $validator = Validator::make(
            $request->all(),
            [
                'cedula_cliente' => 'required',
                'nombre_completo_cliente' => 'required',
                'telefono_cliente' => 'required',
                'tipo_pedido' => 'required',
                'fecha' => 'required',
                'direccion' => 'required',
                'subtotal' => 'required',
                'impuesto' => 'required',
                'envio' => 'required',
                'total' => 'required',
                'estado' => 'required',
                'provincia_id' => 'required',
                'personal_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }
        DB::beginTransaction();
        try {
            $pedido = new Pedido();
            $pedido->fecha = Carbon::parse(Carbon::now())->format('Y-m-d');
            //$user= auth('api')->user();
            //$pedido->user()->associate($user-id);
            $pedido->save();
            //Detalles
            $detalles = $request->input('detalles');
            foreach ($detalles as $item) {
                $pedido->productos()->attach($item['idItem'], [
                    'cantidad' => $item['cantidad'],
                    'total' => $item['total']
                ]);
            }
            DB::commit();
            $response = "¡Pedido creado correctamente!";
            return response()->json($response, 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(), 422);
        }
    }


    public function storeapi(Request $request)
    {
        //
        //
        $validator = Validator::make(
            $request->all(),
            [
                'cedula_cliente' => 'required',
                'nombre_completo_cliente' => 'required',
                'telefono_cliente' => 'required',
                'tipo_pedido' => 'required',
                'fecha' => 'required',
                'direccion' => 'required',
                'subtotal' => 'required',
                'impuesto' => 'required',
                'envio' => 'required',
                'total' => 'required',
                'estado' => 'required',
                'provincia_id' => 'required',
                'personal_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }
        DB::beginTransaction();
        try {
            $pedido = new Pedido();
            $pedido->cedula_cliente = $request->input('cedula_cliente');
            $pedido->nombre_completo_cliente = $request->input('nombre_completo_cliente');
            $pedido->telefono_cliente = $request->input('telefono_cliente');
            $pedido->tipo_pedido = $request->input('tipo_pedido');
            $pedido->fecha = $request->input('fecha');
            $pedido->direccion = $request->input('direccion');
            $pedido->subtotal = $request->input('subtotal');
            $pedido->impuesto = $request->input('impuesto');
            $pedido->envio = $request->input('envio');
            $pedido->total = $request->input('total');
            $pedido->estado = $request->input('estado');
            $pedido->provincia_id = $request->input('provincia_id');
            $pedido->personal_id = $request->input('personal_id');

            //$user= auth('api')->user();
            //$pedido->user()->associate($user-id);
            $pedido->save();
            DB::commit();
            $response = "¡Pedido creado correctamente!";
            return response()->json($response, 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage(), 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            //listar
            $pedido = Pedido::where('id', $id)->with(['provincia', 'personal', 'productos'])->get()->first();
            $response = $pedido;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 422);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedido $pedido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pedido $pedido)
    {
        //
    }
}
