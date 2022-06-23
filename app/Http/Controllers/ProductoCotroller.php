<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categorias;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoCotroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //Selecionar todos los priductos 
       $productos=producto::all();
       //mostrar  vista catologo de productos
       //llevando la lista de productos 
       return view('productos.index')
       ->with('productos', $productos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Seleccionar todas las marcas 
        $marcas = Marca::all();

        //Seleccionar todas las categorias 
        $categorias = Categorias::all();

        //Mostrar la vista d enuevo producto
        //enviandole slos datos d emarca y categorias

        return view('productos.create')
              ->with('marcas' , $marcas)
              ->with('categorias' , $categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        //Validacion 
        //1. establecer reglas de validacion 
        $reglas=[
            "nombre" => 'required|alpha|unique:productos,nombre',
            "desc" => 'required|min:5max:20',
            "precio" => 'required|numeric',
            "imagen" => 'required|image',
            "marca" => 'required',
            "categoria" => 'required'
        ];
        //2.  crear el objeto validador
        $v = Validator::make($r->all() , $reglas );

        //3.Validar
        if($v->fails()){
            //si la validacion fallo
            //redirigirme a la vista de create (ruta: productos/create)
            //Con los mensajes 

            return redirect('productos/create')
                ->withErrors($v);
        }else{
            //validacion exitosa

                    //examinar el archivo cargado 
        
        $archivo=$r->imagen;

        //obtener el nombre original del archivo 

        $nombre_archivo=$archivo->getClientOriginalName();

        //establecer la ubicacion de guardado de archivo
        $ruta=public_path()."/img";

        //mover el archivo de imagen a la ubicacion y nombre deseado
        $archivo->move($ruta , $nombre_archivo);

        
        
        //Crear un nuevo producto 
        $p = new Producto();
        $p ->nombre = $r ->nombre;
        $p ->desc =$r ->desc;
        $p ->precio = $r ->precio; 
        $p ->marca_id = $r ->marca;
        $p ->categoria_id = $r -> categoria;
        $p ->imagen = $nombre_archivo;

        //grabar producto 
        $p->save();
        
        //redirigir a productos/create
        //con un mensaje de exito 
        
        return redirect('productos/create')
             ->with('mensajito', 'producto registrado existosamente');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show($producto)
    {
        echo "aqui va el detalle del producto con id: $producto";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit($producto)
    {
        echo "aqui va el form para actualizar producto";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(producto $producto)
    {
        //
    }
}
