<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class NuevoAnimalAgregado extends Notification
{
    use Queueable;


    protected $validatedData = [];
    protected $usuario;

    /**
     * Create a new notification instance.
     */
    public function __construct( $validatedData, $usuario)
    {
        $this->validatedData = $validatedData;
        $this->usuario = $usuario;
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
 
        $imagePath = storage_path('app/public/' . $this->validatedData['imagen_principal']);
    
        return (new MailMessage)
                    ->subject('🚨¡Un nuevo integrante se une a la familia!🚨')
                    ->markdown('emails.nuevo-animal', [
                        'usuario' => $this->usuario,
                        'animal' => $this->validatedData,
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
