$(document).ready(function () {
    $("#submitEstimate").click(function (event) {
      event.preventDefault(); // Evita que el formulario se envíe automáticamente

      // Obtener los datos del formulario
      var fullname = $("#fullname").val();
      var phone = $("#phone").val();
      var email = $("#email").val();
      var description = $("#description").val();
      var url = $(this).data('url');
      var id_product = $(this).data('product-id');
      // Enviar los datos al controlador
      $.ajax({
        url: url,
        type: "POST",
        data: {
          action: "submitestimate",
          ajax: 1,
          fullname: fullname,
          phone: phone,
          email: email,
          description: description,
          id_product: id_product,
        },
        dataType: "json",
        success: function (response) {
          // Procesar la respuesta del controlador
          if (response.success) {
            // Mostrar un mensaje de éxito
            toastr.success(response.message);
          } else {
            // Mostrar un mensaje de error
            toastr.warning(response.message);
          }
        },
        error: function (xhr, status, error) {
          if (xhr.status === 400) {
            // Si la petición falla con un código de estado 400, es porque hubo un error en la validación
            toastr.warning(xhr.responseJSON.message);
          } else {
            // Si la petición falla con cualquier otro código de estado, es porque hubo un error en el servidor
            toastr.error(error.message);
          }
        },
      });
  
      // Cerrar el modal
      $("#modalEstimate").modal("hide");
    });
  });
  