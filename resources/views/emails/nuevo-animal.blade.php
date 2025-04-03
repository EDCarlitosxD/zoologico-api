@component('mail::message')
# ¡Hola {{ $usuario->nombre_usuario }}! 🎉

Tenemos excelentes noticias: **¡Un nuevo animal ha llegado a nuestro zoológico!**

@component('mail::panel')
## {{ $animal['nombre'] }}

<img src="{{ $message->embed($imagen) }}" 
alt="{{ $animal['nombre'] }}"
style="max-width: 500px;">

**Tipo:** {{ $animal['tipo'] }}  
**Habitat:** {{ $animal['habitat'] }}  
**Descripción:** {{ $animal['descripcion'] }}
@endcomponent



¡Te esperamos!  
**El equipo del Zoológico** 🐘🦒
@endcomponent