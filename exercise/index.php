<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'] ?? 'Guest';

// ---- Include database connection ----
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/auth.php';

// Verify database connection
if (!$con) {
    die("Database connection failed.");
}

$flash = null;

// ---- Handle form submission for adding new exercise ----
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['exercise_type'])) {
    $exercise_type    = trim($_POST['exercise_type'] ?? '');
    $duration_minutes = (int)($_POST['duration_minutes'] ?? 0);
    $calories_burnt   = (int)($_POST['calories_burnt'] ?? 0);
    $exercise_date    = $_POST['exercise_date'] ?? '';
    $notes            = trim($_POST['notes'] ?? '');

    try {
        $stmt = $con->prepare("INSERT INTO exercises 
            (user_id, exercise_type, duration_minutes, calories_burnt, exercise_date, notes, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("isiiis", $_SESSION['user_id'], $exercise_type, $duration_minutes, $calories_burnt, $exercise_date, $notes);
        $stmt->execute();
        $flash = ["type" => "success", "text" => "New exercise record created successfully."];
        $stmt->close();
    } catch (Exception $e) {
        $flash = ["type" => "error", "text" => "Error: " . htmlspecialchars($e->getMessage())];
    }
}

// ---- Handle delete request ----
if (isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    try {
        $stmt = $con->prepare("DELETE FROM exercises WHERE exercise_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $deleteId, $_SESSION['user_id']);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $flash = ["type" => "success", "text" => "Exercise record deleted successfully."];
        } else {
            $flash = ["type" => "error", "text" => "Failed to delete exercise record."];
        }
        $stmt->close();
    } catch (Exception $e) {
        $flash = ["type" => "error", "text" => "Error deleting: " . htmlspecialchars($e->getMessage())];
    }
}

// ---- Handle update request ----
if (isset($_POST['update_id'])) {
    $updateId = (int)$_POST['update_id'];
    $newDuration = (int)($_POST['duration_minutes'] ?? 0);
    $newCalories = (int)($_POST['calories_burnt'] ?? 0);
    try {
        $stmt = $con->prepare("UPDATE exercises SET duration_minutes=?, calories_burnt=?, updated_at=NOW() WHERE exercise_id=? AND user_id=?");
        $stmt->bind_param("iiii", $newDuration, $newCalories, $updateId, $_SESSION['user_id']);
        $stmt->execute();
        if ($stmt->affected_rows >= 0) {
            $flash = ["type" => "success", "text" => "Exercise record updated successfully."];
        } else {
            $flash = ["type" => "error", "text" => "Failed to update exercise record."];
        }
        $stmt->close();
    } catch (Exception $e) {
        $flash = ["type" => "error", "text" => "Error updating: " . htmlspecialchars($e->getMessage())];
    }
}

// ---- Fetch overall stats for current user ----
$stats = ["total_minutes" => 0, "total_calories" => 0, "total_sessions" => 0];
$resultStats = $con->prepare("
    SELECT 
        COALESCE(SUM(duration_minutes),0) AS total_minutes, 
        COALESCE(SUM(calories_burnt),0)   AS total_calories,
        COUNT(*)                          AS total_sessions 
    FROM exercises
    WHERE user_id = ?
");
$resultStats->bind_param('i', $_SESSION['user_id']);
$resultStats->execute();
$resStats = $resultStats->get_result();
if ($resStats) {
    $stats = $resStats->fetch_assoc();
    $resStats->free();
}
$resultStats->close();

// ---- Last 7 days summary for current user ----
$last7 = ["minutes" => 0, "calories" => 0, "sessions" => 0];
$result7 = $con->prepare("
    SELECT 
        COALESCE(SUM(duration_minutes),0) AS minutes,
        COALESCE(SUM(calories_burnt),0)   AS calories,
        COUNT(*)                          AS sessions
    FROM exercises
    WHERE user_id = ? AND exercise_date >= (CURDATE() - INTERVAL 7 DAY)
");
$result7->bind_param('i', $_SESSION['user_id']);
$result7->execute();
$res7 = $result7->get_result();
if ($res7) {
    $last7 = $res7->fetch_assoc();
    $res7->free();
}
$result7->close();

// ---- Handle search & date filters ----
$search   = $_GET['search'] ?? '';
$dateFrom = $_GET['dateFrom'] ?? '';
$dateTo   = $_GET['dateTo'] ?? '';

$where = ["user_id = ?"];
$params = [];
$types = 'i';
$params[] = &$_SESSION['user_id'];

if ($search !== '') {
    $where[] = "(exercise_type LIKE ? OR notes LIKE ?)";
    $searchWildcard = "%$search%";
    $params[] = &$searchWildcard;
    $params[] = &$searchWildcard;
    $types .= 'ss';
}

if ($dateFrom !== '') {
    $where[] = "exercise_date >= ?";
    $params[] = &$dateFrom;
    $types .= 's';
}
if ($dateTo !== '') {
    $where[] = "exercise_date <= ?";
    $params[] = &$dateTo;
    $types .= 's';
}

$sql = "SELECT * FROM exercises";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY exercise_date DESC, created_at DESC";

$stmt = $con->prepare($sql);
if ($stmt && $params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Exercise Tracker — Student Routine Organizer</title>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&family=Jua&family=Zen+Maru+Gothic&display=swap">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="topbar">
        <div class="brand-mini">Student Routine Organizer · <span class="muted">Exercise Tracker</span></div>
        <div class="tools">
            <div class="user-info">
                <span class="welcome">Welcome <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            </div>
            <button class="toggle" id="modeToggle" title="Toggle light/dark">🌓 Theme</button>
            <button class="toggle" id="exportCsvBtn" title="Export CSV">⬇️ Export</button>
        </div>
    </div>

    <header class="card">
        <div class="title">Exercise Tracker</div>
        <p class="subtitle">Log workouts, track calories and minutes, and see your weekly progress. <span class="notice">Works hard, Play hard</span></p>
        <div style="position:absolute;right:-30px;bottom:-30px;opacity:.18;font-size:180px;font-weight:800;">🏃‍♀️</div>
    </header>

    <div class="grid grid-2" style="margin-top:16px">
        <!-- Left: Table & Filters -->
        <section class="card">
            <div class="card-body">
                <div class="section-title">Exercise Records</div>
                <form method="GET" class="tools" style="margin-bottom:10px">
                    <input name="search" type="text" placeholder="Search by type or notes…" value="<?= htmlspecialchars($search) ?>">
                    <input name="dateFrom" type="date" title="From date" value="<?= htmlspecialchars($dateFrom) ?>">
                    <span class="muted">to</span>
                    <input name="dateTo" type="date" title="To date" value="<?= htmlspecialchars($dateTo) ?>">
                    <button type="submit" class="btn secondary">Search</button>
                    <button type="button" class="btn" id="clearFilters">Clear</button>
                </form>

                <div class="table-wrap">
                    <table id="recordsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Duration (min)</th>
                                <th>Calories</th>
                                <th>Date</th>
                                <th>Notes</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr data-id="<?= (int)$row['exercise_id'] ?>">
                                    <td><?= htmlspecialchars($row['exercise_id']) ?></td>
                                    <td><?= htmlspecialchars($row['exercise_type']) ?></td>
                                    <td class="duration"><?= (int)$row['duration_minutes'] ?></td>
                                    <td class="calories"><?= (int)$row['calories_burnt'] ?></td>
                                    <td><?= htmlspecialchars($row['exercise_date']) ?></td>
                                    <td><?= htmlspecialchars($row['notes']) ?></td>
                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                    <td><?= htmlspecialchars($row['updated_at']) ?></td>
                                    <td>
                                        <button type="button" class="btn secondary editBtn">Edit</button>
                                        <form method="POST" onsubmit="return confirm('Delete this record?');" style="display:inline">
                                            <input type="hidden" name="delete_id" value="<?= (int)$row['exercise_id'] ?>">
                                            <button type="submit" class="btn secondary">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="9">No records found</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="notice" style="margin-top:8px">Tip: use search and date filters to quickly find past workouts.</div>
            </div>
        </section>

        <!-- Right: Form & Stats -->
        <aside class="grid" style="gap:16px">
            <section class="card">
                <div class="card-body">
                    <div class="section-title">Add New Exercise</div>
                    <form method="POST" class="form-grid" id="exerciseForm" novalidate>
                        <div class="full">
                            <label>Exercise Type</label>
                            <input type="text" name="exercise_type" list="exerciseTypeList" placeholder="e.g., Running" required>
                            <datalist id="exerciseTypeList">
                                <option>Running</option>
                                <option>Cycling</option>
                                <option>Swimming</option>
                                <option>Gym</option>
                                <option>Yoga</option>
                                <option>HIIT</option>
                                <option>Walking</option>
                                <option>Basketball</option>
                            </datalist>
                        </div>

                        <div>
                            <label>Duration (minutes)</label>
                            <input type="number" name="duration_minutes" min="1" step="1" placeholder="30" required>
                        </div>
                        <div>
                            <label>Calories Burnt</label>
                            <input type="number" name="calories_burnt" min="0" step="1" placeholder="250" required>
                        </div>

                        <div>
                            <label>Date</label>
                            <input type="date" name="exercise_date" required>
                        </div>
                        <div class="full">
                            <label>Notes</label>
                            <textarea name="notes" placeholder="Optional notes…"></textarea>
                        </div>

                        <div class="btn-group full">
                            <button type="submit" class="btn">Save</button>
                            <button type="reset" class="btn secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </section>

            <section class="card">
                <div class="card-body">
                    <div class="section-title">Statistics</div>
                    <div class="stats">
                        <div class="stat">
                            <div class="label">Total Minutes</div>
                            <div class="value"><?= (int)$stats['total_minutes'] ?></div>
                        </div>
                        <div class="stat">
                            <div class="label">Total Calories</div>
                            <div class="value"><?= (int)$stats['total_calories'] ?></div>
                        </div>
                        <div class="stat">
                            <div class="label">Total Sessions</div>
                            <div class="value"><?= (int)$stats['total_sessions'] ?></div>
                        </div>
                    </div>
                    <div style="margin-top:12px">
                        <span class="chip">Last 7 days: <?= (int)$last7['sessions'] ?> sessions</span>
                        <span class="chip">⏱ <?= (int)$last7['minutes'] ?> min</span>
                        <span class="chip">🔥 <?= (int)$last7['calories'] ?> cal</span>
                    </div>
                </div>
            </section>
        </aside>
    </div>
</div>

<?php $con->close(); ?>

<?php if ($flash): ?>
<div class="toast <?= $flash['type'] === 'success' ? 'success' : 'error' ?>" id="toast">
    <span><?= htmlspecialchars($flash['text']) ?></span>
</div>
<script>
setTimeout(()=>{ const t=document.getElementById('toast'); if(t){ t.style.opacity='0'; t.style.transform='translateY(10px)'; setTimeout(()=>t.remove(), 300); } }, 3000);
</script>
<?php endif; ?>

<script>
document.getElementById('clearFilters').addEventListener('click', function() {
    window.location.href = window.location.pathname;
});

// ---- Theme Toggle ----
const modeToggle = document.getElementById("modeToggle");
modeToggle?.addEventListener("click", () => {
    document.body.classList.toggle("light");
    if (document.body.classList.contains("light")) {
        localStorage.setItem("theme", "light");
    } else {
        localStorage.setItem("theme", "dark");
    }
});
window.addEventListener("DOMContentLoaded", () => {
    const theme = localStorage.getItem("theme");
    if (theme === "light") {
        document.body.classList.add("light");
    }
});

// ---- Export CSV ----
const exportBtn = document.getElementById("exportCsvBtn");
exportBtn?.addEventListener("click", () => {
    const table = document.getElementById("recordsTable");
    if (!table) return;

    let csv = [];
    for (let row of table.rows) {
        let cols = [];
        for (let cell of row.cells) {
            cols.push('"' + cell.innerText.replace(/"/g, '""') + '"');
        }
        csv.push(cols.join(","));
    }

    const blob = new Blob([csv.join("\n")], { type: "text/csv" });
    const url = URL.createObjectURL(blob);

    const a = document.createElement("a");
    a.href = url;
    a.download = "exercise_records.csv";
    a.click();
    URL.revokeObjectURL(url);
});

// ---- Inline Edit ----
document.querySelectorAll(".editBtn").forEach(btn => {
    btn.addEventListener("click", function() {
        const row = this.closest("tr");
        const id = row.dataset.id;
        const durationCell = row.querySelector(".duration");
        const caloriesCell = row.querySelector(".calories");

        if (this.innerText === "Edit") {
            // Convert to inputs
            const currentDuration = durationCell.innerText.trim();
            const currentCalories = caloriesCell.innerText.trim();

            durationCell.innerHTML = `<input type="number" name="duration_minutes" min="1" value="${currentDuration}" style="width:80px">`;
            caloriesCell.innerHTML = `<input type="number" name="calories_burnt" min="0" value="${currentCalories}" style="width:80px">`;

            this.innerText = "Save";
        } else {
            // Submit update
            const newDuration = durationCell.querySelector("input").value;
            const newCalories = caloriesCell.querySelector("input").value;

            const form = document.createElement("form");
            form.method = "POST";
            form.style.display = "none";

            form.innerHTML = `
                <input type="hidden" name="update_id" value="${id}">
                <input type="hidden" name="duration_minutes" value="${newDuration}">
                <input type="hidden" name="calories_burnt" value="${newCalories}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>

<script src="script.js"></script>
</body>
</html>
