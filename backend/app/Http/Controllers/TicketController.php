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
        // Validar los datos recibidos
        $validated = $request->validate([
            'sessionmovies_id' => 'required|integer|exists:sessionmovies,id', // Cambiar sessions por sessionmovies
            'seats' => 'required|array', // Asientos seleccionados
            'user_id' => 'required|integer|exists:users,id', // ID del usuario
            'hour' => 'required|string', // Hora de la sesión
        ]);

        // Obtener la sesión correspondiente
        $session = SessionMovie::find($validated['sessionmovies_id']);
        if (!$session) {
            return response()->json(['message' => 'Sesión no encontrada.'], 404);
        }

        // Verificar si ya existe una compra previa para el usuario y la sesión
        $existingTicket = Ticket::where('sessionmovies_id', $validated['sessionmovies_id'])
                                ->where('user_id', $validated['user_id'])
                                ->first();

        if ($existingTicket) {
            return response()->json(['message' => 'Ya has comprado entradas para esta sesión.'], 400);
        }

        // Decodificar el JSON de asientos
        $seats = json_decode($session->seats, true);
        
        // Comprobar y actualizar el estado de los asientos seleccionados
        foreach ($validated['seats'] as $selectedSeat) {
            $row = $selectedSeat['row'];
            $seat = $selectedSeat['seat'];

            // Verificar si la fila existe
            if (!isset($seats[$row])) {
                return response()->json(['message' => "La fila $row no existe."], 400);
            }

            // Buscar el asiento en la fila
            $seatIndex = array_search($seat, array_column($seats[$row], 'seat'));

            if ($seatIndex !== false && $seats[$row][$seatIndex]['status'] === 'available') {
                // Marcar el asiento como ocupado
                $seats[$row][$seatIndex]['status'] = 'occupied';
            } else {
                return response()->json(['message' => "El asiento $seat de la fila $row ya está ocupado o no existe."], 400);
            }
        }

        // Guardar el nuevo estado de los asientos en la base de datos
        $session->seats = json_encode($seats);
        $session->save();

        // Crear el ticket para cada asiento seleccionado
        foreach ($validated['seats'] as $selectedSeat) {
            Ticket::create([
                'sessionmovies_id' => $validated['sessionmovies_id'],
                'user_id' => $validated['user_id'],
                'seat' => $selectedSeat['seat'],
                'row' => $selectedSeat['row'], // Asegúrate de guardar la fila también
                'hour' => $validated['hour'],
            ]);
        }

        return response()->json(['message' => 'Compra realizada con éxito'], 200);
    }
}

