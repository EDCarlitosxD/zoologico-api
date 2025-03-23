<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="Usuario",
 *     description="Modelo de Usuario",
 *     required={"nombre_usuario", "nombre", "apellido", "email", "password", "rol"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre_usuario", type="string", example="johndoe"),
 *     @OA\Property(property="nombre", type="string", example="John"),
 *     @OA\Property(property="apellido", type="string", example="Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", example="2024-03-01T12:34:56Z"),
 *     @OA\Property(property="estado", type="boolean", example=true),
 *     @OA\Property(property="rol", type="string", example="admin")
 * )
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'nombre_usuario',
        'nombre',
        'apellido',
        'email',
        'password',
        'email_verified_at',
        'estado',
        'rol',
    ];

    protected $table = 'usuarios';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
