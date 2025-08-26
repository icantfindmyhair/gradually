<?php
// ---- DB Connection ----
$host = "localhost";
$user = "root";
$password = "";
$dbname = "gradually_db";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // nicer errors in dev
try {
    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database connection failed.");
}

$flash = null; // for toast message

// ---- Handle form submission ----
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $exercise_type    = trim($_POST['exercise_type'] ?? '');
    $duration_minutes = (int)($_POST['duration_minutes'] ?? 0);
    $calories_burnt   = (int)($_POST['calories_burnt'] ?? 0);
    $exercise_date    = $_POST['exercise_date'] ?? '';
    $notes            = trim($_POST['notes'] ?? '');

    try {
        $stmt = $conn->prepare("INSERT INTO exercises (exercise_type, duration_minutes, calories_burnt, exercise_date, notes, created_at, updated_at) 
                                VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("siiss", $exercise_type, $duration_minutes, $calories_burnt, $exercise_date, $notes);
        $stmt->execute();
        $flash = ["type" => "success", "text" => "New exercise record created successfully."];
        $stmt->close();
    } catch (Exception $e) {
        $flash = ["type" => "error", "text" => "Error: " . htmlspecialchars($e->getMessage())];
    }
}

// ---- Stats ----
$stats = [
    "total_minutes"  => 0,
    "total_calories" => 0,
    "total_sessions" => 0
];

$resultStats = $conn->query("
    SELECT 
        COALESCE(SUM(duration_minutes),0) AS total_minutes, 
        COALESCE(SUM(calories_burnt),0)   AS total_calories,
        COUNT(*)                          AS total_sessions 
    FROM exercises
");
if ($resultStats) {
    $stats = $resultStats->fetch_assoc();
    $resultStats->free();
}

// Last 7 days summary
$last7 = ["minutes" => 0, "calories" => 0, "sessions" => 0];
$result7 = $conn->query("
    SELECT 
        COALESCE(SUM(duration_minutes),0) AS minutes,
        COALESCE(SUM(calories_burnt),0)   AS calories,
        COUNT(*)                          AS sessions
    FROM exercises
    WHERE exercise_date >= (CURDATE() - INTERVAL 7 DAY)
");
if ($result7) {
    $last7 = $result7->fetch_assoc();
    $result7->free();
}

// ---- Fetch all records ----
$result = $conn->query("SELECT * FROM exercises ORDER BY exercise_date DESC, created_at DESC");

// We keep connection open until the end of HTML to allow num_rows checks, then close.
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Exercise Tracker — Student Routine Organizer</title>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">

</head>
<body>
<div class="container">
    <div class="topbar">
        <div class="brand-mini">Student Routine Organizer · <span class="muted">Exercise Tracker</span></div>
        <div class="tools">
            <button class="toggle" id="modeToggle" title="Toggle light/dark">🌓 Theme</button>
            <button class="toggle" id="exportCsvBtn" title="Export CSV">⬇️ Export</button>
        </div>
    </div>

    <header class="card">
        <div class="title">Exercise Tracker</div>
        <p class="subtitle">Log workouts, track calories and minutes, and see your weekly progress. <span class="notice">All fields unchanged to keep backend compatibility.</span></p>
        <div style="position:absolute;right:-30px;bottom:-30px;opacity:.18;font-size:180px;font-weight:800;">🏃‍♀️</div>
    </header>

    <div class="grid grid-2" style="margin-top:16px">
        <!-- Left: Table & Filters -->
        <section class="card">
            <div class="card-body">
                <div class="section-title">Exercise Records</div>
                <div class="tools" style="margin-bottom:10px">
                    <input id="searchInput" type="text" placeholder="Search by type or notes…">
                    <input id="dateFrom" type="date" title="From date">
                    <span class="muted">to</span>
                    <input id="dateTo" type="date" title="To date">
                    <button class="btn secondary" id="clearFilters">Clear</button>
                </div>
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
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['exercise_id']) ?></td>
                                    <td><?= htmlspecialchars($row['exercise_type']) ?></td>
                                    <td><?= (int)$row['duration_minutes'] ?></td>
                                    <td><?= (int)$row['calories_burnt'] ?></td>
                                    <td><?= htmlspecialchars($row['exercise_date']) ?></td>
                                    <td><?= htmlspecialchars($row['notes']) ?></td>
                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                    <td><?= htmlspecialchars($row['updated_at']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8">No records found</td></tr>
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

<?php $conn->close(); ?>

<?php if ($flash): ?>
<div class="toast <?= $flash['type'] === 'success' ? 'success' : 'error' ?>" id="toast">
    <span><?= htmlspecialchars($flash['text']) ?></span>
</div>
<script>
    setTimeout(()=>{ const t=document.getElementById('toast'); if(t){ t.style.opacity='0'; t.style.transform='translateY(10px)'; setTimeout(()=>t.remove(), 300); } }, 3000);
</script>
<?php endif; ?>

<script>
// --- Theme toggle (persists) ---
(function(){
    const key='exercise-theme';
    const saved=localStorage.getItem(key);
    if(saved==='light') document.documentElement.classList.add('light');
    const btn=document.getElementById('modeToggle');
    btn.addEventListener('click',()=>{
        document.documentElement.classList.toggle('light');
        localStorage.setItem(key, document.documentElement.classList.contains('light') ? 'light' : 'dark');
    });
})();

// --- Table filter & CSV export ---
(function(){
    const q = (s)=>document.querySelector(s);
    const rows = ()=>Array.from(document.querySelectorAll('#recordsTable tbody tr'));
    const search = q('#searchInput');
    const from = q('#dateFrom');
    const to = q('#dateTo');
    const clear = q('#clearFilters');

    function within(dateStr, fromStr, toStr){
        if(!dateStr) return false;
        const d = new Date(dateStr);
        if(fromStr){ const f = new Date(fromStr); if(d < f) return false; }
        if(toStr){ const t = new Date(toStr); if(d > t) return false; }
        return true;
    }

    function applyFilters(){
        const text = (search.value||'').toLowerCase();
        const f = from.value; const t = to.value;
        rows().forEach(tr=>{
            const tds = tr.querySelectorAll('td');
            const type  = (tds[1]?.textContent||'').toLowerCase();
            const notes = (tds[5]?.textContent||'').toLowerCase();
            const date  = (tds[4]?.textContent||'').trim();
            const textMatch = !text || type.includes(text) || notes.includes(text);
            const dateMatch = (!f && !t) || within(date, f, t);
            tr.style.display = (textMatch && dateMatch) ? '' : 'none';
        });
    }

    search.addEventListener('input', applyFilters);
    from.addEventListener('change', applyFilters);
    to.addEventListener('change', applyFilters);
    clear.addEventListener('click', ()=>{
        search.value=''; from.value=''; to.value=''; applyFilters();
    });

    // CSV export
    document.getElementById('exportCsvBtn').addEventListener('click', ()=>{
        const visibleRows = rows().filter(tr=>tr.style.display!=='none');
        const all = [Array.from(document.querySelectorAll('#recordsTable thead th')).map(th=>th.innerText.trim())];
        visibleRows.forEach(tr=>{
            all.push(Array.from(tr.querySelectorAll('td')).map(td=>td.innerText.replace(/\n/g,' ').trim()));
        });
        const csv = all.map(r=>r.map(cell=>{
            const c = cell.replace(/"/g,'""');
            return `"${c}"`;
        }).join(',')).join('\n');
        const blob = new Blob([csv],{type:'text/csv;charset=utf-8;'});
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'exercise_records.csv';
        a.click();
        URL.revokeObjectURL(a.href);
    });

    // Simple client-side required validation styling
    document.getElementById('exerciseForm').addEventListener('submit', (e)=>{
        const form = e.currentTarget;
        if(!form.checkValidity()){
            e.preventDefault();
            Array.from(form.elements).forEach(el=>{
                if(el.willValidate && !el.checkValidity()){
                    el.scrollIntoView({behavior:'smooth', block:'center'});
                }
            });
        }
    });
})();
</script>
</body>
</html>