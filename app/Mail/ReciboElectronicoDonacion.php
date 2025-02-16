<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReciboElectronicoDonacion extends Mailable
{
    use Queueable, SerializesModels;

    public $datos = [];
    public $email, $nombre, $fecha;
    public function __construct($datos, $email, $nombre, $fecha)
    {
        $this->datos = $datos;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->fecha = $fecha;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recibo Electrónico',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.Vistavacia', 
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.ReciboDona', [
            "datos" => $this->datos,
            "email" => $this->email,
            "nombre" => $this->nombre,
            "fecha" => $this->fecha
        ]);

        $path = storage_path('app/descargas/recibo_electronicodonacion.pdf');

        $pdf->save($path);


        return [$path];
    }
}
