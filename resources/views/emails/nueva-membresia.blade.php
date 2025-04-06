@component('mail::mesage')
# ¡Hola {{ $usuario->nombre_usuario }}! 🎉

Tenemos increíbles noticias... **¡Tenemos una membresía disponible para ti!**

@component('mail::panel')
##{{$membresia['nombre']}}

**Precio:** {{$membresia['precio']}}
**Descuento en tours:** {{$membresia['descuento_tours'].'%'}}
**Precio especial a invitados:** {{'$'.$membresia['precio_especial_invitados']}}

@endcomponent

¡Te esperamos!  
**El equipo del Zoológico** 🐘🦒
@endcomponent