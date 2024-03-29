<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$curr_file = basename($_SERVER['SCRIPT_FILENAME']);
$lang_dir = basename(dirname($_SERVER['SCRIPT_FILENAME']));
// echo "<br>$lang_dir<br>";

// STATUS CODES FOR DIFFERENT LANGUAGES!!!
// This is especially useful as the project scales bigger.

$conn_failed;
$username_short;
$username_long;
$invalid_username;
$bad_password;
$unmatching_passwords;
$username_exists;

$successful_register;

$undefined_error;

switch ($lang_dir) {
    case "en":
        $conn_failed = "Connection failed: ";
        $username_short = "Please enter at least a three character long username.";
        $username_long = "Please enter a username that's shorter than 20 symbols.";
        $invalid_username = "Please only use values from a-z, A-Z, 0-9 and up to 1 underscore.";
        $bad_password = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one digit.";
        $unmatching_passwords = "Passwords don't match.";
        $username_exists = "Username already exists. Please choose a different username.";

        $successful_register = "New account created successfully!";

        $undefined_error = "Error: ";
        break;
    case "bg":
        $conn_failed = "Неуспешна връзка с базата от данни: ";
        $username_short = "Моля въведете поне 3 символа във вашето име.";
        $username_long = "Моля въведете по-малко от 20 символа във вашето име.";
        $invalid_username = "Моля въведете стойности от a-z, A-Z, 0-9 и максимум една долна черта.";
        $bad_password = "Паролата Ви трябва да съдържа поне 8 символа, една главна буква, една малка буква и едно число.";
        $unmatching_passwords = "Паролите не съвпадат.";
        $username_exists = "Потребителското име съществува, моля изберете друго.";

        $successful_register = "Успешна регистрация!";

        $undefined_error = "Грешка: ";
        break;
    default:
        # code...
        break;
}

function isValidUsername($str) {
    // Check if the string contains alphanumeric characters and up to one underscore
    return preg_match('/^[a-zA-Z0-9]*(_?[a-zA-Z0-9]*)*$/', $str);
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "ligma";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("$conn_failed" . $conn->connect_error);
}

// Create database if it doesn't exist
$createDatabaseQuery = "CREATE DATABASE IF NOT EXISTS pc_store";
$conn->query($createDatabaseQuery);
/*
if ($conn->query($createDatabaseQuery) === TRUE) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . $conn->error;
}
*/

// Select the database
$conn->select_db("pc_store");

// Create table if it doesn't exist
$createTableQuery = "CREATE TABLE IF NOT EXISTS users (
    id INT(8) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(32) UNIQUE,
    password VARCHAR(128)
)";
$conn->query($createTableQuery);

/*
if ($conn->query($createTableQuery) === TRUE) {
    echo "Table created successfully or already exists<br>";
} else {
    echo "Error creating table: " . $conn->error;
}
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extracting password
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validating username
    if (strlen($username) < 3) {
        echo "$username_short";
        return;
    }

    if (strlen($username) > 20) {
        echo "$username_long";
        return;
    }

    if (!isValidUsername($username)) {
        echo "$invalid_username";
        return;
    }


    // Validating password
    if (strlen($password) < 8 || !preg_match("/[a-z]/", $password) || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
        echo "$bad_password";
        return;
    }
    if ($password !== $confirm_password) {
        echo "$unmatching_passwords";
        return;
    }

    // Check if username already exists...
    $checkUsernameQuery = "SELECT * FROM users WHERE name = ?";
    $stmt = $conn->prepare($checkUsernameQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "$username_exists";
        return;
    }

    // Generate password hash (we store data SAFELY!)
    $hashed_password = password_hash($password, PASSWORD_ARGON2I);

    $stmt = $conn->prepare("INSERT INTO users (name, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    if (!$stmt) {
        echo "$undefined_error" . $conn->error;
        return;
    }

    // After checking for your silly inputs and attempts for SQL injections
    // And other stupid shit like using a NULL password or a 1 character password
    // We FINALLY execute your query.
    $stmt->execute();

    echo "$successful_register";

    // Close connection
    $conn->close();

}
?>
