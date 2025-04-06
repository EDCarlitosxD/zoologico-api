<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CuentaService{

    public function update($request, $idusuario){
        $validacion = $request->validate([
            "nombre" => 'required|max:255',
            "apellido" => 'required|max:255',
            "password" => 'required|max:255',
            "foto_perfil" => 'nullable'
        ]);
    
        $registro = User::findOrFail($idusuario);
    
        if ($request->hasFile('foto_perfil')) {

            if ($registro->foto_perfil && Storage::exists($registro->foto_perfil)) {
                Storage::delete($registro->foto_perfil);
            }
    
            $rutaFoto = $request->file('foto_perfil')->store('Usuario', 'public');
            $validacion['foto_perfil'] = $rutaFoto;
        }
    
        $registro->update([
            'nombre' => $validacion['nombre'],
            'apellido' => $validacion['apellido'],
            'password' => Hash::make($validacion['password']),
            'foto_perfil' => $validacion['foto_perfil'] ?? $registro->foto_perfil
        ]);
    
        return response()->json(["message" => "Usuario actualizado con éxito"]);
    }




}




?>