<?php
// verify.php
include 'futsal_db.php';
$token = $_GET['token'];
$sql = "SELECT * FROM stadium_email_verification WHERE token=? AND expiry >= NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Activate user
    
    $update = $conn->prepare("UPDATE stadium SET status='active' WHERE stadium_id=?");
    $update->bind_param("i", $row['client_id']);
    $update->execute();
    $_SESSION['user_id'] = $row['client_id'];
    $_SESSION['role'] = 'client';
    header("Location: Profile.php");
} else {
    echo "Invalid or expired token.";
}

?>