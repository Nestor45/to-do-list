<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Task filtering by status. Funcion que nos devuelve las tareas deacuerdo a su estado
     * 
     * @return \Illuminate\Http\Response
     */
    public function getTaskStatus(Request $request) {

        $tasks = Task::where('status', $request->status)->get(); //obtenemos las tareas por estado
        $array = array(); //Creamos un areglo para almacenar las tareas y devolverlas
        
        foreach($tasks as $task) {
            $user = User::whereId($task->user_id)->first(); // obtenemos el usuario al que fue asignado la tarea (por si las dudas)
            
            $objectTask = new \stdClass();
            $objectTask->id_task = $task->id;
            $objectTask->title = $task->title;
            $objectTask->description = $task->description;
            $objectTask->status = $task->status;
            $objectTask->url = $task->url;
            $objectTask->user_name = $user->name;
            $objectTask->id_user = $user->id;
            array_push($array, $objectTask);
        }
        if ($array) {
            return response()->json([
                "tasks" => $array
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "No hay nada en la BD"
            ], 250);
        }
    }

    /**
     * Show the form for creating a new resource. Funcion que nos ayuda a agregar tareas la BD
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $exito = false;
        DB::beginTransaction();

        try {
            $task = new Task;
            $task->title = $request->title;
            $task->description = $request->description;
            $task->status = $request->status;
            $task->url = $request->url;
            $task->user_id = $request->user_id;
            $task->save();

            DB::commit();
            $exito = true;

        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Error al intentar guardar registros"
            ], 500);
        }

        if($exito){
            return response()->json([
                "status" => "ok",
                "message" => "Task guardado con exito",
                "task" => $task
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage. Funcion que nos permite actualizar los datos de la tarea
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editTask(Request $request)
    {
        $exito = false;
        DB::beginTransaction();
        
        try {
            $task = Task::find($request->id_task);
            $task->title = $request->title;
            $task->description = $request->description;
            $task->status = $request->status;
            $task->url = $request->url;
            $task->user_id = $request->user_id;
            
            $task->save();
            DB::commit();
            $exito = true;

        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Error al intentar guardar registros"
            ], 500);
        }

        if($exito){
            return response()->json([
                "status" => "ok",
                "message" => "Task guardado con exito",
                "task" => $task
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exito = false;
        DB::beginTransaction();
        try {

            $task = Task::findOrFail($id); //findOrFail -> abortara el proceso si no se encuentra el registro retornando un abort(404)
            $task->delete();
            DB::commit();
            $exito = true;

        } catch (\Throwable $th) {
            DB::rollback();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Error al eliminar los datos",
                "error" => $th
            ], 500);
        }

        if($exito){
            return response()->json([
                "status" => "ok",
                "message" => "Task eliminado con exito",
            ], 200);
        }
    }

    /**
     * Change the specified state of storage. Funcion que nos permite colocar en CANCELADO en lugar de borrar el regitro
     * @return \Illuminate\Http\Response
     */
    public function destroyStatus(Request $request) {

        $exito = false;
        DB::beginTransaction();

        try {
            $task = Task::find($request->id_task); //find -> Solo nos retorna un null
            $task->status = 'cancelado';
            $task->save();

            DB::commit();
            $exito = true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $exito = false;
            return response()->json([
                "status" => "error",
                "message" => "Error al eliminar los datos",
                "error" => $th
            ], 500);
        }

        if ($exito) {
            return response()->json([
                "status" => "ok",
                "message" => "Tarea eliminada correctamente",
                "question" => $task
            ], 200);
        }
    }
}
