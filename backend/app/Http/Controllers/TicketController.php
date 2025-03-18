<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\SessionMovie; // Modelo de sesión de película
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // Crear una nueva entrada para una sesión
    public function comprarEntrada(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'session_movie_id' => 'required|exists:session_movies,id',
        'asientos' => 'required|array', // Lista de asientos a comprar
        'asientos.*.row' => 'required|string',
        'asientos.*.asiento_number' => 'required|integer',
    ]);

    DB::beginTransaction();

    try {
        // Crear el ticket
        $ticket = Ticket::create([
            'user_id' => $request->user_id,
            'session_movie_id' => $request->session_movie_id,
        ]);

        // Marcar los asientos como ocupados y vincularlos al ticket
        foreach ($request->asientos as $seat) {
            $asiento = Asiento::where('session_movie_id', $request->session_movie_id)
                ->where('row', $seat['row'])
                ->where('asiento_number', $seat['asiento_number'])
                ->first();

            if ($asiento && $asiento->status === 'available') {
                $asiento->update(['status' => 'occupied']);
                TicketAsiento::create([
                    'ticket_id' => $ticket->id,
                    'asiento_id' => $asiento->id,
                ]);
            } else {
                return response()->json(['error' => 'Uno o más asientos ya están ocupados'], 400);
            }
        }

        DB::commit();
        return response()->json(['message' => 'Compra realizada con éxito'], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Error en la compra', 'details' => $e->getMessage()], 500);
    }
}


}

