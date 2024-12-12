<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_id = $_POST['vehicle_id'];
    $result = $conn->query("SELECT * FROM vehicle WHERE id='$vehicle_id'");
    $vehicle = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $vehicle_number = $_POST['vehicle_number'];
    $owner_name = $_POST['owner_name'];
    
    $sql = "UPDATE vehicle SET vehicle_number='$vehicle_number', owner_name='$owner_name' WHERE id='$vehicle_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Vehicle</title>
</head>
<body>
    <h2>Edit Vehicle</h2>
    <form method="POST">
        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">
        <div>
            <input type="text" name="vehicle_number" value="<?php echo $vehicle['vehicle_number']; ?>" required>
        </div>
        <div>
            <input type="text" name="owner_name" value="<?php echo $vehicle['owner_name']; ?>" required>
        </div>
        <button type="submit" name="update">Update Vehicle</button>
    </form>
</body>
</html>
