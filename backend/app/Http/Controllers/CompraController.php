<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\SpecialPrice;
use App\Models\SessionMovie;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'session_movie_id' => 'required|exists:session_movies,id',
            'seats' => 'required|array|min:1',
            'seats.*.row' => 'required|string',
            'seats.*.seat' => 'required|integer',
            'total_price' => 'required|numeric',
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email',
        ]);
    
        // Si el usuario está autenticado, puedes obtenerlo de auth(), de lo contrario, usa los datos enviados
        $user = auth()->user();
        $ticketData = [
            'user_id' => $user ? $user->id : null,
            'name' => $user ? null : $request->name,
            'surname' => $user ? null : $request->surname,
            'email' => $user ? null : $request->email,
            'session_movie_id' => $request->session_movie_id,
            'seats' => json_encode($request->seats),
            'total_price' => $request->total_price,
        ];
    
        $ticket = Ticket::create($ticketData);
    
        // Aquí podrías guardar la información de los asientos seleccionados en otra tabla si lo deseas
    
        return response()->json(['message' => 'Compra realizada con éxito', 'ticket' => $ticket], 201);
    }

    // En el controlador `CompraController.php` o `TicketController.php`
public function obtenerAsientosOcupados($idSesionPelicula)
{
    // Obtener los tickets de la sesión de la película
    $entradas = Ticket::where('session_movie_id', $idSesionPelicula)
                      ->pluck('seats') // Obtener los asientos ocupados
                      ->toArray();

    // Convertir los asientos ocupados en una lista de asientos
    $asientosOcupados = [];
    foreach ($entradas as $entrada) {
        foreach (json_decode($entrada) as $asiento) {
            $asientosOcupados[] = $asiento->row . $asiento->seat;
        }
    }

    // Devolver los asientos ocupados
    return response()->json(['asientosOcupados' => $asientosOcupados]);
}

    
}

