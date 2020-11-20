<!doctype html>
<html lang="en" class="h-100">
  <head>
    @include('includes._head')

    @stack('styles')
  </head>
  <body class="d-flex flex-column h-100">
    <header>
      @include('includes._header')
    </header>
    
    <main role="main" class="container">
      <div class="row mt-3">
        <div class="col-12">       
          @yield('content')
        </div>
      </div>
    </main>
    
    @include('includes._footer')

    <script src="{{ mix('js/app.js') }}"></script>

    @stack('scripts')
  </body>
</html>