<?php

// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ], [
        // Mensajes personalizados
        'password.min' => 'Necesitas 6 caracteres como mínimo para la contraseña.',
    ]);


    if ($validator->fails()) {
        return response()->json([
            'message' => 'Errores de validación',
            'errors' => $validator->errors(),
        ], 422);
    }

    // Crear usuario
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return response()->json([
        'message' => 'Usuario registrado correctamente',
        'user' => $user
    ], 201);
}

    // Login de usuario
public function login(Request $request)
{
    // Validación de los datos
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Verificar si las credenciales son correctas
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user();
        
        // Crear el token con Sanctum (esto devolverá un token en texto plano)
        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json([
            'message' => 'Usuario logueado exitosamente',
            'token' => $token,  // Devolvemos el token en texto plano
            'user' => $user,
        ], 200);
    }

    return response()->json([
        'message' => 'Credenciales incorrectas',
    ], 401);
}




    // Logout de usuario
    public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Usuario desconectado exitosamente']);
}

}
