<?php
// Database connection
$servername = "localhost";  // Replace with your database server
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "final_project";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $salesAmount = $_POST['salesAmount'];
  $costAmount = $_POST['costAmount'];

  // Calculate income amount
  $incomeAmount = $salesAmount - $costAmount;

  // Validate the input
  if (!empty($salesAmount) && !empty($costAmount)) {
    // Prepare the SQL query
    $sql = "INSERT INTO income (sales_amount, cost_amount, income_amount) VALUES ('$salesAmount', '$costAmount', '$incomeAmount')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
      // After successful insertion, redirect to the income-costs.php page
      header("Location: income-costs.php");
      exit; // Make sure the script stops after the redirect
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  } else {
    echo "All fields are required.";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Income</title>
  <link rel="stylesheet" href="../styles/sidebar.css">
  <link rel="stylesheet" href="../styles/topbar.css">
  <link rel="stylesheet" href="../styles/income.css">

  <!-- Font Awesome for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <script>
    // JavaScript to auto-calculate the income amount
    function calculateIncome() {
      var salesAmount = parseFloat(document.getElementById('salesAmount').value) || 0;
      var costAmount = parseFloat(document.getElementById('costAmount').value) || 0;
      var incomeAmount = salesAmount - costAmount;

      // Set the value of the income amount field
      document.getElementById('incomeAmount').value = incomeAmount.toFixed(2);
    }

    // Add event listeners to the sales amount and cost amount fields
    window.onload = function() {
      document.getElementById('salesAmount').addEventListener('input', calculateIncome);
      document.getElementById('costAmount').addEventListener('input', calculateIncome);
    };
  </script>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <ul>
      <li><a href="../dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
        <li><a href="suppliers.php"><i class="fas fa-truck"></i> Suppliers</a></li>
        <li><a href="budget.php"><i class="fas fa-coins"></i> Budget</a></li>
        <li><a href="costs.php"><i class="fas fa-money-bill-wave"></i> Costs</a></li>
        <li><a href="income-costs.php"><i class="fas fa-file-invoice-dollar"></i> Income</a></li>
        <li><a href="sales.php"><i class="fas fa-chart-line"></i> Sales</a></li>
        <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li><a href="customers.php"><i class="fas fa-users"></i> Customer Management</a></li>
        <li><a href="shipment.php"><i class="fas fa-shipping-fast"></i> Shipment</a></li>
        <li><a href="purches.php"><i class="fas fa-money-bill-wave"></i> Purchase</a></li>
        <li><a href="roles.php"><i class="fas fa-user-cog"></i> Role Management</a></li>
      </ul>
      <button id="logout-btn" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Log out</button>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Top Bar -->
      <header>
        <div class="top-bar">
          <div class="logo">
            <img src="../assets/logo.jpg" alt="Logo" style="height: 50px;">
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

      <h1>Add New Income</h1>

      <!-- Add Income Form -->
      <form class="add-income-form" method="POST">
        <div class="form-group">
          <label for="salesAmount">Sales Amount (LKR)</label>
          <input type="number" id="salesAmount" name="salesAmount" required>
        </div>

        <div class="form-group">
          <label for="costAmount">Cost Amount (LKR)</label>
          <input type="number" id="costAmount" name="costAmount" required>
        </div>

        <div class="form-group">
          <label for="incomeAmount">Income Amount (LKR)</label>
          <input type="number" id="incomeAmount" name="incomeAmount" readonly>
        </div>

        <div class="form-group">
          <button type="submit">Add Income</button>
        </div>

        <p class="error-message" id="formErrorMessage"></p>
      </form>
    </main>
  </div>
</body>
</html>
