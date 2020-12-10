<!doctype html>
<html lang="es" class="h-100">
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
          {{ $slot }}
        </div>
      </div>
    </main>
    
    @include('includes._footer')

    <script src="{{ mix('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.10/js/gijgo.min.js"></script>
    
    <script>
      $('#from').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy'
      });
      $('#to').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy'
      });
    </script>
  </body>
</html>