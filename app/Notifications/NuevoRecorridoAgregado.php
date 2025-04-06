<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NuevoRecorridoAgregado extends Notification
{
    use Queueable;

    protected $usuario;
    protected $recorrido = [];
    
    public function __construct($recorrido, $usuario)
    {
        $this->usuario = $usuario;
        $this->recorrido = $recorrido;
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
        $imagePath =  storage_path('app/public/' . $this->recorrido['img_recorrido']);
        return (new MailMessage)
                    ->subject("📢¡Atención, nuevo recorrido disponible!📢")
                    ->markdown('emails.nuevo-recorrido', [
                        'usuario' => $this->usuario,
                        'recorrido' => $this->recorrido,
                        'imagen' => $imagePath
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
