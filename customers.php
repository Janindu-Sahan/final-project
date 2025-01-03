<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "final_project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerName = $_POST['customerName'];
    $customerEmail = $_POST['customerEmail'];
    $customerPhone = $_POST['customerPhone'];

    // Check if all fields are filled
    if (empty($customerName) || empty($customerEmail) || empty($customerPhone)) {
        echo json_encode(array("status" => "error", "message" => "All fields are required."));
        exit;
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO customers (customerName, customerEmail, customerPhone) VALUES (?, ?, ?)");
    if ($stmt === false) {
        echo json_encode(array("status" => "error", "message" => "Statement preparation failed."));
        exit;
    }
    $stmt->bind_param("sss", $customerName, $customerEmail, $customerPhone);

    // Execute the statement
    if ($stmt->execute()) {
        $response = array("status" => "success", "message" => "Customer added successfully.");
    } else {
        $response = array("status" => "error", "message" => "Failed to add customer: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();

    // Return response in JSON format
    echo json_encode($response);
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Management - Royalty Program</title>
  <link rel="stylesheet" href="styles/sidebar.css">
  <link rel="stylesheet" href="styles/topbar.css">
  <link rel="stylesheet" href="styles/customer.css">

  <!-- Font Awesome for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <!-- QR Code Library -->
  <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <ul>
        <li><a href="dashboard.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="inventory.html"><i class="fas fa-boxes"></i> Inventory</a></li>
        <li><a href="suppliers.html"><i class="fas fa-truck"></i> Suppliers</a></li>
        <li><a href="budget.html"><i class="fas fa-coins"></i> Budget</a></li>
        <li><a href="costs.html"><i class="fas fa-money-bill-wave"></i> Costs</a></li>
        <li><a href="income-costs.html"><i class="fas fa-file-invoice-dollar"></i> Income </a></li>
        <li><a href="sales.html"><i class="fas fa-chart-line"></i> Sales</a></li>
        <li><a href="orders.html"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li><a href="customers.html"><i class="fas fa-users"></i> Customer Management</a></li>
        <li><a href="shipment.html"><i class="fas fa-shipping-fast"></i> Shipment </a></li>
        <li><a href="purches.html"><i class="fas fa-money-bill-wave"></i>Purchase</a></li>
        <li><a href="roles.html"><i class="fas fa-user-cog"></i> Role Management</a></li>
      </ul>
      <button class="logout-btn"><i class="fas fa-sign-out-alt"></i> Log out</button>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Top Bar -->
      <header>
        <div class="top-bar">
          <div class="logo">
            <img src="assets/logo.jpg" alt="Logo" style="height: 50px;">
          </div>
          <div class="search-bar">
            <input type="text" placeholder="Type for search">
          </div>
          <div class="user-icons">
            <span class="icon"><i class="fas fa-bell"></i></span>
            <span class="icon"><i class="fas fa-comments"></i></span>
            <a href="profile.html">
              <span class="icon"><i class="fas fa-user-circle"></i></span>
            </a>
          </div>
        </div>
      </header>

      <h1>Add New Customer (Royalty Program)</h1>

      <!-- Add Customer Form -->
      <form class="add-customer-form" id="addCustomerForm">
        <div class="form-group">
          <label for="customerName">Customer Name</label>
          <input type="text" id="customerName" name="customerName" required>
        </div>

        <div class="form-group">
          <label for="customerEmail">Email Address</label>
          <input type="email" id="customerEmail" name="customerEmail" required>
        </div>

        <div class="form-group">
          <label for="customerPhone">Phone Number</label>
          <input type="text" id="customerPhone" name="customerPhone" required>
        </div>

        <div class="form-group">
          <button type="submit">Add Customer</button>
        </div>

        <p class="error-message" id="formErrorMessage"></p>
      </form>

      <!-- Royalty Card Display -->
      <div class="card" id="royaltyCard" style="display:none;">
        <h2>Royalty Card</h2>
        <p>Name: <span id="customerNameDisplay"></span></p>
        <p>Customer ID: <span id="customerIdDisplay"></span></p>
        <div class="qr-code" id="qrCode"></div>
        <button class="card-button" onclick="window.print()">Print Card</button>
      </div>
    </main>
  </div>

  <script>
    document.getElementById('addCustomerForm').addEventListener('submit', function(event) {
      event.preventDefault();
  
      const customerName = document.getElementById('customerName').value;
      const customerEmail = document.getElementById('customerEmail').value;
      const customerPhone = document.getElementById('customerPhone').value;
      const errorMessage = document.getElementById('formErrorMessage');
  
      // Validate the form fields
      if (customerName && customerEmail && customerPhone) {
        // Send data to the backend using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "", true);  // Posting to the same file
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  
        xhr.onload = function () {
          const response = JSON.parse(xhr.responseText);
  
          if (response.status === "success") {
            // Generate a unique ID for the customer
            const customerId = 'CUST-' + Math.floor(Math.random() * 1000000);
  
            // Display the customer info on the card
            document.getElementById('customerNameDisplay').textContent = customerName;
            document.getElementById('customerIdDisplay').textContent = customerId;
  
            // Generate the QR code
            const qrCode = new QRCode(document.getElementById("qrCode"), {
              text: customerId,
              width: 128,
              height: 128
            });
  
            // Show the royalty card and hide the form
            document.getElementById('addCustomerForm').reset();
            document.getElementById('royaltyCard').style.display = 'block';
            
            // Show a success alert
            alert("Customer added successfully!");
          } else {
            errorMessage.textContent = response.message;
          }
        };
  
        xhr.send("customerName=" + encodeURIComponent(customerName) + 
                 "&customerEmail=" + encodeURIComponent(customerEmail) + 
                 "&customerPhone=" + encodeURIComponent(customerPhone));
      } else {
        errorMessage.textContent = 'All fields are required.';
      }
    });
  </script>
</body>
</html>
