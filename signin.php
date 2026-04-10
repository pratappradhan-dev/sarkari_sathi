<?php
session_start();
include(__DIR__ . "/../config/config.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($pass, $user['password'])) {

            // ✅ SESSION SET
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // ✅ IMPORTANT FIX (correct path)
            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Wrong Password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <!-- Container -->
    <div class="bg-white shadow-lg rounded-2xl flex overflow-hidden w-[800px]">

        <!-- Left Side (Image / Branding) -->
        <div class="hidden md:flex w-1/2 bg-blue-600 items-center justify-center text-white p-8">
            <div>
                <h1 class="text-3xl font-bold mb-4">Welcome Back!</h1>
                <p class="text-sm opacity-80">
                    Login to continue your journey with us.  
                    Stay connected and explore more features.
                </p>
            </div>
        </div>

        <!-- Right Side (Login Form) -->
        <div class="w-full md:w-1/2 p-8">

            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                Login
            </h2>

            <form action="" method="POST" class="space-y-5">

                <!-- Email -->
                <div>
                    <label class="block text-gray-600 mb-1">Email</label>
                    <input 
                        type="email" 
                        name="email"
                        placeholder="Enter your email"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-gray-600 mb-1">Password</label>
                    <input 
                        type="password" 
                        name="password"
                        placeholder="Enter your password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>

                <!-- Remember + Forgot -->
                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" class="accent-blue-600">
                        Remember me
                    </label>

                    <a href="#" class="text-blue-600 hover:underline">
                        Forgot Password?
                    </a>
                </div>

                <!-- Button -->
                <button 
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200"
                >
                    Login
                </button>

            </form>

            <!-- Signup -->
            <p class="text-sm text-center mt-6 text-gray-600">
                Don't have an account?
                <a href="signup.php" class="text-blue-600 font-semibold hover:underline">
                    Sign Up
                </a>
            </p>

        </div>
    </div>

</body>
</html>