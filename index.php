<?php
session_start();
include 'db.php';

// User authentication check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check in a vehicle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['check_in'])) {
    $vehicle_number = $_POST['vehicle_number'];
    $owner_name = $_POST['owner_name'];

    $sql = "INSERT INTO vehicle (vehicle_number, owner_name) VALUES ('$vehicle_number', '$owner_name')";
    if ($conn->query($sql) === TRUE) {
        echo "Vehicle checked in successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Check out a vehicle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['check_out'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $sql = "UPDATE vehicle SET status='Checked Out' WHERE id='$vehicle_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Vehicle checked out successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Search functionality
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $_POST['search_query'];
}

$result = $conn->query("SELECT * FROM vehicle WHERE status='Parked' AND (vehicle_number LIKE '%$search_query%' OR owner_name LIKE '%$search_query%')");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Parking Management System</title>
</head>
    <div class="container mt-5">
        <h2>Parking Management System</h2>
        
        <!-- Check-in Form -->
        <form method="POST" class="mb-4">
            <div class="form-group">
                <input type="text" name="vehicle_number" class="form-control" placeholder="Vehicle Number" required>
            </div>
            <div class="form-group">
                <input type="text" name="owner_name" class="form-control" placeholder="Owner Name" required>
            </div>
            <button type="submit" name="check_in" class="btn btn-primary">Check In Vehicle</button>
        </form>

        <!-- Search Form -->
        <form method="POST" class="mb-4">
            <div class="form-group">
                <input type="text" name="search_query" class="form-control" placeholder="Search by Vehicle Number or Owner Name" value="<?php echo $search_query; ?>">
            </div>
            <button type="submit" name="search" class="btn btn-secondary">Search</button>
        </form>

        <!-- Parked Vehicles List -->
        <h3>Parked Vehicles</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vehicle Number</th>
                    <th>Owner Name</th>
                    <th>Check-In Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['vehicle_number']; ?></td>
                        <td><?php echo $row['owner_name']; ?></td>
                        <td><?php echo $row['check_in_time']; ?></td>
                        <td>
                            <form method="POST" action="edit.php">
                                <input type="hidden" name="vehicle_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-warning">Edit</button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="vehicle_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="check_out" class="btn btn-danger">Check Out</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
