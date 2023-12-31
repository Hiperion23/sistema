<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>System</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">SYKO</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link active" aria-current="page" href="../Index.php">Home</a>
        <a class="nav-link" href="./Supplier.php">Supplier</a>
        <a class="nav-link" href="./Purchase.php">Purchase</a>
        <a class="nav-link" href="./Payment.php">Payment</a>
      </div>
    </div>
    <form class="d-flex" role="search">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  </div>
</nav>

<!-- Button -->
<button type="button" class="btn btn-danger float-end mt-4" data-bs-toggle="modal" data-bs-target="#paymentModal">
  Create new Payment
</button>

<!-- Modal -->
<div class="modal fade" id="paymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Payment Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Create Payment Form -->
                <form id="createPaymentForm">
                    <div class="mb-3">
                        <label for="idPurchase" class="form-label">Purchase ID</label>
                        <input type="text" class="form-control" id="idPurchase" name="idPurchase" required>
                    </div>
                    <div class="mb-3">
                        <label for="paymentAmount" class="form-label">Payment Amount</label>
                        <input type="number" class="form-control" id="paymentAmount" name="paymentAmount" required>
                    </div>
                    <div class="mb-3">
                        <label for="paymentDate" class="form-label">Payment Date</label>
                        <input type="datetime-local" class="form-control" id="paymentDate" name="paymentDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" class="form-control" id="status" name="status" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="createPaymentForm" id="createPaymentButton" class="btn btn-danger">Create</button>
            </div>
        </div>
    </div>
</div>

<!-- Payment List -->
<h3 class="mt-4">Payment List</h3>
<ul id="paymentList" class="list-group"></ul>

<script>
function handleSuccess(response) {
  alert("Payment Correct")
  $("#paymentModal").modal("hide");
  $("#createPaymentForm")[0].reset();
}

function handleFailure(response) {
  if (response.status === 200){
    alert("Payment Correct") //Esta linea no es necesario
    $("#paymentModal").modal("hide");
    $("#createPaymentForm")[0].reset();
  }else{
    alert("Error al crear el pago: "+ response.message);
  }
}

$("#createPaymentForm").on("submit", function(event) {
  event.preventDefault();

  const formData = new FormData(document.querySelector("#createPaymentForm"));
  const paymentData = {};

  for (const pair of formData.entries()) {
    paymentData[pair[0]] = pair[1];
  }

  $.ajax({
    type: "POST",
    url: "../Controllers/PaymentController.php",
    data: JSON.stringify(paymentData),
    dataType: "json",
    contentType: "application/json",
    success: handleSuccess,
    error: handleFailure
  });

});

document.addEventListener("DOMContentLoaded", function() {
  $.ajax({
    type: "GET", 
    url: "../Controllers/PaymentController.php",
    dataType: "json",
    success: function(data) {
      var paymentList = document.getElementById("paymentList");

      data.forEach(payment => {
        var listItem = document.createElement("li");
        listItem.className = "list-group-item";
        listItem.textContent = `${payment.idPurchase} - ${payment.paymentAmount} - ${payment.paymentDate} - ${payment.status}`;
        var editButton = document.createElement("button");
      editButton.textContent = "Editar";
      editButton.className = "btn btn-warning btn-sm float-end mx-1";
      editButton.addEventListener("click", function() {
      // Cargar datos en el formulario de creación para la edición
      $("#idPurchase").val(payment.idPurchase);
      $("#paymentAmount").val(payment.paymentAmount);
      $("#paymentDate").val(payment.paymentDate);
      $("#status").val(payment.status);
      $("#createPaymentButton").text("Editar").off("click").on("click", function() {
        // Lógica para editar el elemento (puedes hacer una solicitud AJAX al servidor)
        alert("Editar elemento con ID: " + payment.id);
        // Cerrar el modal después de la edición
        $("#paymentModal").modal("hide");
      });

      // Abrir el modal de creación/editación
      $("#paymentModal").modal("show");
    });

    // Botón de Eliminar
    var deleteButton = document.createElement("button");
    deleteButton.textContent = "Eliminar";
    deleteButton.className = "btn btn-danger btn-sm float-end";
    deleteButton.addEventListener("click", function() {
      if (confirm("¿Estás seguro de que quieres eliminar este elemento?")) {
        // Lógica para eliminar el elemento (puedes hacer una solicitud AJAX al servidor)
        alert("Eliminar elemento con ID: " + payment.id);
        // También puedes remover el elemento de la lista visualmente
        paymentList.removeChild(listItem);
      }
    });

        listItem.appendChild(editButton);
        listItem.appendChild(deleteButton);
        paymentList.appendChild(listItem);
      });
    },
    error: function(error) {
      console.error("AJAX error:", error);
    }
  });
});



</script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>