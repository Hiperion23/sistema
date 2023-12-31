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

<!-- Button to open the modal -->
<button type="button" class="btn btn-danger float-end mt-4" data-bs-toggle="modal" data-bs-target="#purchaseModal">
  Create new Purchase
</button>

<!-- Modal -->
<div class="modal fade" id="purchaseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="purchaseModalLabel">Purchase Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Create Purchase Form -->
                <form id="createPurchaseForm">
                    <div class="mb-3">
                        <label for="idSupplier" class="form-label">Supplier ID</label>
                        <input type="text" class="form-control" id="idSupplier" name="idSupplier" required>
                    </div>
                    <div class="mb-3">
                        <label for="purchaseDate" class="form-label">Purchase Date</label>
                        <input type="date" class="form-control" id="purchaseDate" name="purchaseDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="totalAmount" class="form-label">Total Amount</label>
                        <input type="number" class="form-control" id="totalAmount" name="totalAmount" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" class="form-control" id="status" name="status" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="createPurchaseForm" class="btn btn-danger">Create</button>
            </div>
        </div>
    </div>
</div>

<!-- Purchase List -->
<h3 class="mt-4">Purchase List</h3>
<ul id="purchaseList" class="list-group"></ul>

<script>
function handleSuccess(response) {
  alert("Purchase Correct")
  $("#purchaseModal").modal("hide");
  $("#createPurchaseForm")[0].reset();
}

function handleFailure(response) {
  if (response.status === 200){
    alert("Purchase Correct") 
    $("#purchaseModal").modal("hide");
    $("#createPurchaseForm")[0].reset();
  }else{
    alert("Error al crear : "+ response.message);
  }
}

$("#createPurchaseForm").on("submit", function(event) {
  event.preventDefault();

  const formData = new FormData(document.querySelector("#createPurchaseForm"));
  const purchaseData = {};

  for (const pair of formData.entries()) {
    purchaseData[pair[0]] = pair[1];
  }

  $.ajax({
    type: "POST",
    url: "../Controllers/PurchaseController.php",
    data: JSON.stringify(purchaseData),
    dataType: "json",
    contentType: "application/json",
    success: handleSuccess,
    error: handleFailure
  });

});

document.addEventListener("DOMContentLoaded", function() {
  $.ajax({
    type: "GET", 
    url: "../Controllers/PurchaseController.php",
    dataType: "json",
    success: function(data) {
      var purchaseList = document.getElementById("purchaseList");

      data.forEach(purchase => {
        var listItem = document.createElement("li");
        listItem.className = "list-group-item";
        listItem.textContent = `${purchase.idPurchase} - ${purchase.idSupplier} - ${purchase.purchaseDate} - ${purchase.totalAmount	} - ${purchase.status	}`;
        purchaseList.appendChild(listItem);
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