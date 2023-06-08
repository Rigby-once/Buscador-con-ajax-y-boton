<!DOCTYPE html>
<html>
<head>
  <title>Búsqueda con AJAX</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .container {
      margin-top: 50px;
    }
  </style>
</head>
<body>
  <div class="container ">
    <div class="row justify-content-center">
    <div class="card col-9 text-center">
      <h5 class="card-header">Búsqueda con AJAX</h5>
      <div class="card-body">
        <div class="form-group ">
          <input type="text" id="consulta" class="form-control" placeholder="Buscar...">
        </div>
        <div id="tabla-resultados"></div>
        <div id="paginacion"></div>
      </div>
    </div>

    </div>
    
  </div>
  
  <div class="container">
    <div class="row justify-content-center">
    <div class="card col-9 text-center">
      <h5 class="card-header">Búsqueda con Botón</h5>
      <div class="card-body">
        <div class="form-group">
          <input type="text" id="consultas" class="form-control" placeholder="Buscar...">
        </div>
        <button id="buscarconboton" class="btn btn-danger">Buscar</button>
        <div id="tabla-resultados-boton"></div>
        <div id="paginacion-boton"></div>
      </div>
    </div>

    </div>

  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      var registrosPorPagina = 10;
      var paginaActual = 1;
      var totalPaginas = 0;

      function mostrarDatos(datos, identificadorTabla, identificadorPaginacion) {
        var tabla = '<table class="table table-striped">';
        tabla += '<thead><tr><th>Nombres</th><th>Apellidos</th><th>ci</th><th>Nacimiento</th><th>direccion</th><th>Sexo</th><th>Salario</th></tr></thead>';
        tabla += '<tbody>';

        for (var i = 0; i < datos.length; i++) {
          tabla += '<tr>';
          tabla += '<td>' + datos[i]['nombre'] + '</td>';
          tabla += '<td>' + datos[i]['apellido'] + '</td>';
          tabla += '<td>' + datos[i]['ci'] + '</td>';
          tabla += '<td>' + datos[i]['fecha_n'] + '</td>';
          tabla += '<td>' + datos[i]['direccion'] + '</td>';
          tabla += '<td>' + datos[i]['sexo'] + '</td>';
          tabla += '<td>' + datos[i]['salario'] + '</td>';
          tabla += '</tr>';
        }

        tabla += '</tbody></table>';

        $('#' + identificadorTabla).html(tabla);
      }

      function mostrarPaginacion(identificadorPaginacion, identificadorTabla, consulta) {
        var paginacion = '<nav aria-label="Paginación">';
        paginacion += '<ul class="pagination justify-content-center">';

        if (paginaActual > 1) {
          paginacion += '<li class="page-item previous"><a class="page-link" href="#" data-pagina="' + (paginaActual - 1) + '">Anterior</a></li>';
        } else {
          paginacion += '<li class="page-item disabled"><span class="page-link">Anterior</span></li>';
        }

        for (var i = 1; i <= totalPaginas; i++) {
          if (i === paginaActual) {
            paginacion += '<li class="page-item active"><span class="page-link">' + i + '</span></li>';
          } else {
            paginacion += '<li class="page-item"><a class="page-link" href="#" data-pagina="' + i + '">' + i + '</a></li>';
          }
        }

        if (paginaActual < totalPaginas) {
          paginacion += '<li class="page-item next"><a class="page-link" href="#" data-pagina="' + (paginaActual + 1) + '">Siguiente</a></li>';
        } else {
          paginacion += '<li class="page-item disabled"><span class="page-link">Siguiente</span></li>';
        }

        paginacion += '</ul></nav>';

        $('#' + identificadorPaginacion).html(paginacion);

        // Agregar el evento de clic al paginador
        $('#' + identificadorPaginacion + ' a.page-link').click(function(e) {
          e.preventDefault();
          var pagina = parseInt($(this).data('pagina'));

          if (pagina >= 1 && pagina <= totalPaginas) {
            obtenerRegistros(pagina, consulta, identificadorTabla, identificadorPaginacion);
          }
        });
      }

      function obtenerRegistros(pagina, consulta, identificadorTabla, identificadorPaginacion) {
        $.ajax({
          url: 'buscar.php',
          method: 'POST',
          data: { consulta: consulta, pagina: pagina },
          dataType: 'json',
          success: function(data) {
            var registros = data.registros;
            paginaActual = data.paginaActual;
            totalPaginas = data.totalPaginas;

            mostrarDatos(registros, identificadorTabla, identificadorPaginacion);
            mostrarPaginacion(identificadorPaginacion, identificadorTabla, consulta);
          }
        });
      }

      $('#consulta').keyup(function() {
        var consulta = $(this).val().trim();
        paginaActual = 1;

        if (consulta === '') {
          $('#tabla-resultados').empty();
          $('#paginacion').empty();
          return;
        }

        obtenerRegistros(paginaActual, consulta, 'tabla-resultados', 'paginacion');
      });

      $('#buscarconboton').click(function() {
        var consulta = $('#consultas').val().trim();
        paginaActual = 1;

        if (consulta === '') {
          $('#tabla-resultados-boton').empty();
          $('#paginacion-boton').empty();
          return;
        }

        obtenerRegistros(paginaActual, consulta, 'tabla-resultados-boton', 'paginacion-boton');
      });

      $('#consultas').keyup(function(event) {
        if (event.keyCode === 13) {
          var consulta = $(this).val().trim();
          paginaActual = 1;

          if (consulta === '') {
            $('#tabla-resultados-boton').empty();
            $('#paginacion-boton').empty();
            return;
          }

          obtenerRegistros(paginaActual, consulta, 'tabla-resultados-boton', 'paginacion-boton');
        }
      });
    });
  </script>
</body>
</html>
