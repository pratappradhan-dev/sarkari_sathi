<?php
session_start();
include(__DIR__ . "/../config/config.php");

// LOGIN CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $cat = $_POST['category'];

    $image = "";

    // IMAGE UPLOAD
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        $image_name = time() . "_" . $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];

        $upload_path = __DIR__ . "/../uploads/" . $image_name;

        if (move_uploaded_file($tmp_name, $upload_path)) {
            $image = $image_name;
        }
    }

    // INSERT INTO complaints
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO complaints (user_id, title, description, category, image)
         VALUES (?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "issss", $user_id, $title, $desc, $cat, $image);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "DB Error: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <div class="bg-white p-6 rounded-xl shadow w-[400px]">

        <h2 class="text-xl font-bold mb-4">Add Complaint</h2>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-3">

            <input type="text" name="title" placeholder="Title"
                class="w-full border p-2 rounded" required>

            <textarea name="description" placeholder="Description"
                class="w-full border p-2 rounded" required></textarea>

            <select name="category" class="w-full border p-2 rounded" required>
                <option value="">Select Category</option>
                <option value="Road">Road</option>
                <option value="Water">Water</option>
                <option value="Electricity">Electricity</option>
                <option value="Garbage">Garbage</option>
                <option value="Crime">Crime</option>
            </select>

            <input type="file" name="image"
                class="w-full border p-2 rounded" required>

            <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                Submit
            </button>
        </form>

        <a href="dashboard.php" class="block text-center mt-3 text-blue-600">
            Back to Dashboard
        </a>

    </div>

</body>

</html>