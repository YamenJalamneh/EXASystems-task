<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.includes.head')
<body>
<div class="container-scroller">
    @include('layouts.includes.sidebar')
    <div class="container-fluid page-body-wrapper">
        @include('layouts.includes.navbar')
        <div class="main-panel">
            <div class="content-wrapper">
                @yield('breadcrumb')

                @yield('content')
            </div>
         </div>
     </div>
 </div>
 @include('layouts.includes.scripts')
</body>
</html>
