<?php
session_start();
include(__DIR__ . "/../config/config.php");

// LOGIN CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ---------------- SEARCH ---------------- */
$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $sql = "SELECT * FROM complaints 
            WHERE user_id='$user_id' 
            AND title LIKE '%$search%' 
            ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM complaints 
            WHERE user_id='$user_id' 
            ORDER BY id DESC";
}

$result = mysqli_query($conn, $sql);

/* ---------------- STATS ---------------- */

// TOTAL
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM complaints WHERE user_id='$user_id'");
$total = mysqli_fetch_assoc($totalQuery)['total'] ?? 0;

// RESOLVED (dummy logic - later admin status add karna)
$resolved = 0;

// PENDING
$pending = $total - $resolved;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">

    <!-- NAV -->
    <nav class="bg-white shadow px-6 py-4 flex justify-between">
        <h1 class="text-xl font-bold text-blue-600">Sarkari Saathi AI</h1>

        <div class="flex gap-3">
            <a href="process.php" class="bg-blue-600 text-white px-4 py-2 rounded">+ Upload</a>
            <a href="../auth/logout.php" class="bg-red-500 text-white px-4 py-2 rounded">Logout</a>
        </div>
    </nav>

    <div class="p-6">

        <!-- 🔍 SEARCH -->
        <form method="GET" class="mb-6">
            <input type="text" name="search" value="<?php echo $search; ?>"
                placeholder="Search document..."
                class="w-full p-3 border rounded-lg shadow-sm">
        </form>

        <!-- 📊 GRAPH -->
        <div class="bg-white p-6 rounded-xl shadow mb-6">
            <canvas id="chart"></canvas>
        </div>

        <!-- DOCUMENT GRID -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

           <?php while ($row = mysqli_fetch_assoc($result)): ?>

<?php
$name = strtolower($row['title']);
$category = "General";

if (strpos($name, "road") !== false) $category = "Road";
elseif (strpos($name, "water") !== false) $category = "Water";
elseif (strpos($name, "electricity") !== false) $category = "Electricity";
elseif (strpos($name, "garbage") !== false) $category = "Garbage";
elseif (strpos($name, "crime") !== false) $category = "Crime";
?>

<div class="bg-white p-4 rounded-xl shadow">

    <div class="text-4xl text-center">📄</div>

    <h3 class="font-bold mt-2">
        <?php echo $row['title']; ?>
    </h3>

    <p class="text-sm text-blue-500">
        <?php echo $category; ?>
    </p>

    <div class="mt-2">
        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 text-xs rounded">
            Submitted
        </span>
    </div>

    <div class="mt-3 flex gap-2">

        <a href="view.php?id=<?php echo $row['id']; ?>"
           class="bg-blue-600 text-white px-2 py-1 text-sm rounded">
            View
        </a>

        <button onclick="speak('<?php echo $row['title']; ?>')"
            class="bg-green-500 text-white px-2 py-1 text-sm rounded">
            🔊
        </button>

    </div>

</div>

<?php endwhile; ?>

        </div>

    </div>

    <!-- JS SECTION -->

    <script>
        // 📊 CHART
        const ctx = document.getElementById('chart');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Processed', 'Pending'],
                datasets: [{
                    data: [<?php echo $processed; ?>, <?php echo $pending; ?>],
                    borderWidth: 1
                }]
            }
        });

        // 🔊 VOICE
        function speak(text) {
            let speech = new SpeechSynthesisUtterance(text);
            speech.lang = "en-IN";
            speechSynthesis.speak(speech);
        }

        // 🌐 TRANSLATE (redirect)
        function translate(doc_id) {
            window.location.href = "result.php?doc_id=" + doc_id + "&lang=hi";
        }
    </script>

</body>

</html>