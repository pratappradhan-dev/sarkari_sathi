<?php
include(__DIR__ . "/../config/config.php");

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Password hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        $msg = "Email already exists!";
    } else {
        $sql = "INSERT INTO users (name, email, password) 
                VALUES ('$name', '$email', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            $msg = "Account created successfully!";
        } else {
            $msg = "Error creating account!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white shadow-lg rounded-2xl flex overflow-hidden w-[800px]">

    <!-- Left Side -->
    <div class="hidden md:flex w-1/2 bg-green-600 items-center justify-center text-white p-8">
        <div>
            <h1 class="text-3xl font-bold mb-4">Join Us!</h1>
            <p class="text-sm opacity-80">
                Create your account and start your journey with us.
            </p>
        </div>
    </div>

    <!-- Right Side -->
    <div class="w-full md:w-1/2 p-8">

        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
            Sign Up
        </h2>

        <!-- Message -->
        <?php if($msg != ""): ?>
            <div class="bg-blue-100 text-blue-700 p-2 rounded text-sm mb-3">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-5">

            <!-- Name -->
            <div>
                <label class="block text-gray-600 mb-1">Full Name</label>
                <input type="text" name="name" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                    placeholder="Enter your name">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-600 mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                    placeholder="Enter your email">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-gray-600 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                    placeholder="Create password">
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                Create Account
            </button>

        </form>

        <!-- Login Redirect -->
        <p class="text-sm text-center mt-6 text-gray-600">
            Already have an account?
            <a href="signin.php" class="text-green-600 font-semibold hover:underline">
                Login
            </a>
        </p>

    </div>
</div>

</body>
</html>