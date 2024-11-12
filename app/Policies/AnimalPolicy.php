<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Animal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnimalPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Animal $animal): bool
    {
        // ALL USERS CAN BE ANIMAL
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        //ROLE
        //ADMIN O ANIMALES
        return ($user->id_rol == 1 || $user->id_rol == 4) ? Response::allow() : Response::deny("No tienes acceso");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Animal $animal): Response
    {
        //
        return ($user->id_rol == 1 || $user->id_rol == 4) ? Response::allow() : Response::deny("No tienes acceso");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Animal $animal): Response
    {
        //
        return ($user->id_rol == 1 || $user->id_rol == 4) ? Response::allow() : Response::deny("No tienes acceso");
    }

}
