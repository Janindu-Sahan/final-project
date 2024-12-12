<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "final_project"; // Change this to your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch supplier details for editing
if (isset($_GET['id'])) {
    $supplierId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM suppliers WHERE id = ?");
    $stmt->bind_param("i", $supplierId);
    $stmt->execute();
    $result = $stmt->get_result();
    $supplier = $result->fetch_assoc();

    if (!$supplier) {
        echo "<p style='color:red;'>Supplier record not found.</p>";
        exit;
    }
} else {
    echo "<p style='color:red;'>Invalid request. No ID provided.</p>";
    exit;
}

// Update supplier functionality
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplierName = $_POST['supplierName'];
    $supplierEmail = $_POST['supplierEmail'];
    $supplierPhone = $_POST['supplierPhone'];
    $productSupplied = $_POST['productSupplied'];

    // Validate input
    if (empty($supplierName) || empty($supplierEmail) || empty($supplierPhone) || empty($productSupplied)) {
        echo "<p style='color:red;'>All fields are required!</p>";
    } else {
        // Update the supplier record in the database
        $stmt = $conn->prepare("UPDATE suppliers SET supplierName = ?, supplierEmail = ?, supplierPhone = ?, productSupplied = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $supplierName, $supplierEmail, $supplierPhone, $productSupplied, $supplierId);

        if ($stmt->execute()) {
            // Redirect to supplier.php after successful update
            header("Location: supplier.php");
            exit;
        } else {
            echo "<p style='color:red;'>Error: Could not update supplier record.</p>";
        }

        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Supplier</title>
  <link rel="stylesheet" href="styles/sidebar.css">
  <link rel="stylesheet" href="styles/topbar.css">
  <link rel="stylesheet" href="styles/supplier.css">

  <!-- Font Awesome for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <ul>
        <li><a href="dashboard.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="inventory.html"><i class="fas fa-boxes"></i> Inventory</a></li>
        <li><a href="suppliers.html"><i class="fas fa-truck"></i> Suppliers</a></li>
        <li><a href="budget.html"><i class="fas fa-coins"></i>Budget</a></li>
        <li><a href="costs.html"><i class="fas fa-money-bill-wave"></i> Costs</a></li>
        <li><a href="income-costs.html"><i class="fas fa-file-invoice-dollar"></i> Income </a></li>
        <li><a href="sales.html"><i class="fas fa-chart-line"></i> Sales</a></li>
        <li><a href="orders.html"><i class="fas fa-shopping-cart"></i> Orders</a></li>
        <li><a href="customers.html"><i class="fas fa-users"></i> Customer Management</a></li>
        <li><a href="shipment.html"><i class="fas fa-shipping-fast"></i> Shipment </a></li>
        <li><a href="purches.html"><i class="fas fa-money-bill-wave"></i> Purchase</a></li>
        <li><a href="roles.html"><i class="fas fa-user-cog"></i> Role Management</a></li>
      </ul>
      <button id="logout-btn" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Log out</button>
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

      <h1>Edit Supplier</h1>

      <!-- Edit Supplier Form -->
      <form class="edit-supplier-form" id="editSupplierForm" method="POST">

        <div class="form-group">
          <label for="supplierName">Supplier Name</label>
          <input type="text" id="supplierName" name="supplierName" value="<?php echo htmlspecialchars($supplier['supplierName']); ?>" required>
        </div>

        <div class="form-group">
          <label for="supplierEmail">Email Address</label>
          <input type="email" id="supplierEmail" name="supplierEmail" value="<?php echo htmlspecialchars($supplier['supplierEmail']); ?>" required>
        </div>

        <div class="form-group">
          <label for="supplierPhone">Phone Number</label>
          <input type="text" id="supplierPhone" name="supplierPhone" value="<?php echo htmlspecialchars($supplier['supplierPhone']); ?>" required>
        </div>

        <div class="form-group">
          <label for="productSupplied">Products Supplied</label>
          <textarea id="productSupplied" name="productSupplied" required><?php echo htmlspecialchars($supplier['productSupplied']); ?></textarea>
        </div>

        <div class="form-group">
          <button type="submit">Save Changes</button>
        </div>

        <p class="error-message" id="formErrorMessage"></p>
      </form>
    </main>
  </div>
</body>
</html>