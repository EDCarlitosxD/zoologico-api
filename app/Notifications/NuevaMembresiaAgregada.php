<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevaMembresiaAgregada extends Notification
{
    use Queueable;

    protected $usuario;
    protected $membresia = [];

    public function __construct($usuario, $membresia)
    {
        $this->usuario = $usuario;
        $this->membresia = $membresia;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('🚨¡Atención, nueva membresía disponible!🚨')
                    ->markdown('emails.nueva-membresia', [
                        'usuario' => $this->usuario,
                        'membresia' => $this->membresia
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
