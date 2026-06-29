<?php
require_once "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $password);
        $stmt->execute();
        echo "✅ User registered successfully!";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>
<form method="post">
    Username: <input name="username" required><br>
    Email: <input name="email" type="email" required><br>
    Password: <input name="password" type="password" required><br>
    <button type="submit">Register</button>
</form>
