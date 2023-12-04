<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}>
    <head>
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Test_Event</title>

    </head>  
    <body>
        <div style="align-items: center;align-items: baseline;
        display: flex;
        align-content: center;
        flex-wrap: nowrap;
        justify-content: space-around;
        flex-direction: row-reverse;">
            <div><oi id='dataget'>
                This Event :
            </oi>
            </div>
    
        </div>
    </body>
    {{-- <script src="{{asset('resources/js/app.js') }}"></script> --}}
         @vite('resources/js/app.js') 
    <script>   
        setTimeout(() => {
            window.Echo.channel('public-channel').listen('.App\\Events\\TripOrderEvent',(e)=>{
               console.log(e.data);
               var dateget=
               [
                e.data.user_id,
                e.data.fromLate,
                e.data.fromLong,
                e.data.toLate,
                e.data.toLong,
                e.data.price,
                e.data.status,
                e.data.created,
            ];
             document.getElementById("dataget").innerHTML=dateget;
            })
        }, 200);
        setTimeout(() => {
        window.Echo.channel('public-channel').listen('.App\\Events\\TripDeleteEvent',(d)=>{
            console.log(d);
        })
    }, 200);
    setTimeout(() => {
            window.Echo.channel('public-channel').listen('.App\\Events\\ChangeStatusDriverEvent',(e)=>{
               console.log(e.data);
                alert(e.data.status);
            })
        }, 200);

    </script>
</html>