<!doctype html>
<html lang="es">


@include('sections/head')

<body>
    <!-- Preloader -->
    <div id="pagepreloader" class="page-loading clearfix">
        <div class="page-load-inner">
            <div class="preloader-wrap">
                <div class="wrap-2">
                    <div> <img src="{{route('basepath')}}/img/core-img/pre.gif" alt="Preloader"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="ecaps-page-wrapper">
            <div id="app">

                @if (Auth::check()) {{--si el user esta logeado entonces mostramos el contenido--}}
                    <App ruta="{{route('basepath')}}" :usuario="{{Auth::user()->load('rol')}}" ></App>
                @else

                   <Auth ruta="{{route('basepath')}}"></Auth>
                @endif

        </div>
    </div>



    @include('sections/script')



</body>



</html>
