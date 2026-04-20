<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
  <div id="app">
    <div class="main-wrapper">
      @stack('css')
      @include('layouts.navbar')
      @include('layouts.sidebar')    
      @yield('conten')
      @stack('js')
      @include('layouts.footer')
    </div>
  </div>
  @include('layouts.js')
</body>
</html>
