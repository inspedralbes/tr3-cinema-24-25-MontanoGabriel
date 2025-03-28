<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompraConfirmacion extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $apellido;
    public $pelicula;
    public $horario;
    public $asientos;
    public $total_price;
    public $url_poster;

    /**
     * Create a new message instance.
     */
    public function __construct($datos)
    {
        $this->nombre    = $datos['nombre'];
        $this->apellido  = $datos['apellido'];
        $this->pelicula  = $datos['pelicula'];
        $this->horario   = $datos['horario'];
        $this->asientos  = $datos['asientos'];
        $this->total_price     = $datos['total_price'];
        $this->url_poster = $datos['url_poster'] ?? '';
    }

    /**
     * Build the message.
     */
    public function build()
{
    return $this->subject('Confirmación de Compra de Entradas')
                ->view('emails.compra') // Usar la vista en HTML
                ->with([
                    'nombre' => $this->nombre,
                    'apellido' => $this->apellido,
                    'pelicula' => $this->pelicula,
                    'horario' => $this->horario,
                    'asientos' => $this->asientos,
                    'total_price' => $this->total_price,
                    'url_poster' => $this->url_poster, // Asegúrate de pasar el URL
                ]);
}

}

