<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../style/style-main.css">
    <link rel="stylesheet" href="../style/style-forms.css">
    <!--External font-->
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@100&display=swap" rel="stylesheet">
</head>
<body>

    <?php include "../Templates/header.php" ?>

    <?php include "../Templates/navbar.php" ?>

    <div class="mainContent">
        <h1>Register</h1>
        <form method="POST" class="registerForm">
            <label for="username">Username</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required><br>
            <label for="confirm_password">Confirm Password</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br>

            <div class="formRow">
                <input type="checkbox" id="show_password" checked="false">
                <label for="show_password">Show Password</label>
            </div>

            <button type="submit">Register</button>
            <?php include "../Templates/mysql-register.php" ?>
        </form>
    </div>

    <?php include "../Templates/footer.php" ?>

    <script>
    // Uncheck the checkbox when the page loads
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("show_password").checked = false;
        
        // Add event listener to toggle password visibility
        document.getElementById("show_password").addEventListener("change", function() {
            var passwordInput = document.getElementById("password");
            var confirmInput = document.getElementById("confirm_password");

            if (this.checked) {
                passwordInput.type = "text";
                confirmInput.type = "text";
            } else {
                passwordInput.type = "password";
                confirmInput.type = "password";
            }
        });
    });
    </script>

</body>
</html>
