@component('mail::message')
# ¡Hola {{ $usuario->nombre_usuario }}! 🎉

Tenemos noticias interesantes: **¡Se ha agregado un nuevo recorrido a nuestro catálogo!**

@component('mail::panel')
## {{$recorrido['titulo']}}
    
<img src="{{$message->embed($imagen)}}" 
alt="{{$recorrido['titulo']}}"
style="max-width: 500px;">

**Precio:** {{ "$".$recorrido['precio'] }}  
**Duración:** {{$recorrido['duracion']." hr"  }}<br>
**Descripción** {{$recorrido['descripcion']}}
@endcomponent

¡Te esperamos!  
**El equipo del Zoológico** 🐘🦒
@endcomponent