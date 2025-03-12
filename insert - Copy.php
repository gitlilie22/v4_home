
<?php
// بيانات الاتصال بقاعدة البيانات
$servername = "sql112.infinityfree.com"; 
$username = "if0_38156323";
$password = "rnfKDzjkQn";
$dbname = "if0_38156323_form";

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// استلام البيانات من النموذج وتنقيتها
$name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : '';
$phone = isset($_POST['phone']) ? trim(htmlspecialchars($_POST['phone'])) : '';
$message = isset($_POST['message']) ? trim(htmlspecialchars($_POST['message'])) : '';

// التحقق من صحة البيانات
$errors = [];

if (empty($name)) {
    $errors[] = "Name field is required.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
}

if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
    $errors[] = "Invalid phone number. It should contain 10-15 digits.";
}

if (empty($message)) {
    $errors[] = "Message field is required.";
}

// إذا كانت هناك أخطاء، عرضها وإيقاف التنفيذ
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
} else {
    // تحضير الاستعلام وإدخال البيانات
    $stmt = $conn->prepare("INSERT INTO test (name, email, phone, message) VALUES (?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Record added successfully</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>
