<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class studentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        if ($students->isEmpty()) {
            $data = [
                'message' => 'No hay estudiantes registrados',
                'status' => 200
            ];
            return response()->json($data, 404);
        }

        return response()->json($students, 200);
    }


    //todo Este es para guardar
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:student',
            'phone' => 'required|numeric',
            'languaje' => 'required'

        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        //todo: esto es para crear un nuevo estudiante

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'languaje' => $request->languaje
        ]);

        if (!$student) {
            $data = [
                'message' => 'Error al crear el estudiante',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'student' => $student,
            'status' => 201
        ];
        return response()->json($data,201);




    } 
    //esta funcion es para obtener un estudiante por id
    public function show($id){
        $student = Student::find($id); 
        if (!$student) {
            $data = [
                'message'=> 'Estudiante no encontrado',
                'status'=> 404
            ];
            return response()->json($data,404);
        }

         $data = [
            'student'=> $student,
            'status'=> 200
         ];
         return response()->json($data,200);
    }

    public function destroy($id){
        $student = Student::find($id);
        if(!$student){
            $data =[
                'message'=> 'Estudiante no encontrado',
                'status'=> 404
            ];
            return response()->json($data,404);
        }
        $student->delete();

        $data =[
            'message'=> 'Estudiante eliminado correctamente',
            'status'=> 200
        ];
        return response()->json($data,200);
    }

    public function update(Request $request,$id){
        //primero buscamos el estudiante
        $student = Student::find($id);
        if(!$student){
            $data = [
                'message'=> 'Estudiante no encontrado',
                'status'=> 404
            ];
            return response()->json($data,404);
        }

        $validator = Validator::make($request->all(),[
            'name' =>'required',
            'email' =>'required|email|unique:student,email,'.$id,
            'phone' =>'required|numeric',
            'languaje' =>'required'
        ]);
        if($validator->fails()){
            $data = [
                'message'=> 'Error en la validacion de los datos',
                'errors'=> $validator->errors(),
                'status'=> 400
            ];
            return response()->json($data,400);
        }
        //aca actualizamos los datos del estudiante, luego de validarlos
        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->languaje = $request->languaje;
        
        $student->save();

        $data =[
            'student'=> $student,
            'status'=> 200
        ];
        return response()->json($data,200);
        


    }


}
