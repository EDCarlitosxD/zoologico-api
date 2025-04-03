<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Roles",
 *     title="Roles",
 *     description="Modelo de Roles",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Administrador")
 * )
 */
class Roles extends Model
{

    use HasFactory;
}
