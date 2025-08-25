<!DOCTYPE html>
<html lang="en">

<?php
session_start();
require 'database.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habit Tracker</title>

    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&family=Jua&family=Zen+Maru+Gothic&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="habitTracker.css">

    <link rel="stylesheet" href="hamburger.css">
    <?php include 'hamburger.php'; ?>
    <script src="hamburger.js"></script>

</head>

<body>
    <div class="top-bar">
        <button class="hamburger">&#9776;</button>
        <a href="homepage.php" class="title">Gradually</a>
        <a href="logout.php" class="logout-btn">Log out</a>
    </div>

    <div class="main-content">
        <div class="todo-list">
            <div class="todo-header">
                <h2 id="today-date">Saturday, Aug 16</h2>
                <button class="add-btn" id="open-form">+</button>
            </div>

    <?php
    date_default_timezone_set('Asia/Kuala_Lumpur');
$today_day = strtolower(date('l'));
$today_date = date('Y-m-d');
$user_id = $_SESSION['user_id'];
$sql = 'SELECT h.habit_id, h.habit_name, h.description, 
               COALESCE(l.status, 0) AS status
        FROM habit_type h
        LEFT JOIN habit_log l 
               ON h.habit_id = l.habit_id AND l.date = ?
        WHERE h.user_id = ?';
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'si', $today_date, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo 'No habits planned for today.';
}

echo '<ul class="todo-items">';
while ($row = mysqli_fetch_assoc($result)) {
    $checked = $row['status'] ? 'checked' : '';
    $completedClass = $row['status'] ? ' class="completed"' : '';
    echo '<li'.$completedClass.'>';
    echo '<div class="habit-left">';
    echo '<input type="checkbox" class="habit-checkbox" data-habitid="'.$row['habit_id'].'" id="habit'.$row['habit_id'].'" '.$checked.'>';
    echo '<label for="habit'.$row['habit_id'].'">'.htmlspecialchars($row['habit_name']).'</label>';
    echo '</div>';

    echo '<div class="habit-menu">';
    echo '<button class="menu-btn">⋮</button>';
    echo '<div class="menu-dropdown">';
    echo '<button class="edit-btn" data-habitid="'.$row['habit_id'].'">Edit</button>';
    echo '<button class="delete-btn" data-habitid="'.$row['habit_id'].'">Delete</button>';
    echo '</div>';
    echo '</div>';
    echo '</li>';
}
echo '</ul>';
// todo: let user edit habit
?>
        </div>

        <!-- Popup Form -->
        <div class="popup-form" id="popup-form">
            <div class="popup-content">
                <div class="popup-header">
                    <h3>Add A New Habit</h3>
                    <span class="close-btn" id="close-form">&times;</span>
                </div>
                <form action="addHabit.php" method="POST">
                    <label for="habit" class="field-label">Habit Name:</label>
                    <input type="text" id="habit" name="habit" required><br>

                    <label for="remarks" class="field-label">Remarks:</label>
                    <input type="text" id="remarks" name="remarks"><br>

                    <label class="field-label">Repeat:</label>
                    <ul class="repeat-list">
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="monday">
                                <span class="repeat-text">Every Monday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="tuesday">
                                <span class="repeat-text">Every Tuesday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="wednesday">
                                <span class="repeat-text">Every Wednesday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="thursday">
                                <span class="repeat-text">Every Thursday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="friday">
                                <span class="repeat-text">Every Friday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="saturday">
                                <span class="repeat-text">Every Saturday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="sunday">
                                <span class="repeat-text">Every Sunday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                    </ul>

                    <div class="popup-buttons">
                        <button type="submit">Add</button>
                    </div>
                </form>

            </div>
        </div>

        <div class="overview">
            <h2>Habit Overview</h2>
            <p>circle thing to show total habits completed, daily completed percentage, average completion rate (?)</p>
            <p>graph of completion rate for each day for the last 30days/1 year? </p>
        </div>
    </div>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const today = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        document.getElementById("today-date").textContent = today.toLocaleDateString(undefined, options);
    });

    const openFormBtn = document.getElementById('open-form');
    const closeFormBtn = document.getElementById('close-form');
    const popupForm = document.getElementById('popup-form');

    document.getElementById('open-form').addEventListener('click', () => {
        document.getElementById('popup-form').classList.add('is-open');
    });

    document.getElementById('close-form').addEventListener('click', () => {
        document.getElementById('popup-form').classList.remove('is-open');
    });

    openFormBtn.addEventListener('click', () => {
        popupForm.style.display = 'flex';
    });

    closeFormBtn.addEventListener('click', () => {
        popupForm.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === popupForm) {
            popupForm.style.display = 'none';
        }
    });

(function(){
  function closeAll() {
    document.querySelectorAll('.menu-dropdown.show').forEach(d => {
      d.classList.remove('show');
      if (d.style.display === 'block') d.style.display = '';
    });
  }

  console.log('kebab dropdown script initializing');

  document.addEventListener('click', (e) => {
    try {
      const menuBtn = e.target.closest('.menu-btn');
      if (menuBtn) {
        e.stopPropagation();
        const menu = menuBtn.closest('.habit-menu');
        if (!menu) { console.warn('menu button has no .habit-menu parent'); return; }
        const dd = menu.querySelector('.menu-dropdown');
        if (!dd) { console.warn('no .menu-dropdown found inside .habit-menu'); return; }

        document.querySelectorAll('.menu-dropdown.show').forEach(d => { if (d !== dd) d.classList.remove('show'); });

        dd.classList.toggle('show');

        const comp = window.getComputedStyle(dd).display;
        if (comp === 'none') {
          dd.style.display = dd.style.display === 'block' ? '' : 'block';
        } else {
          if (!dd.classList.contains('show')) dd.style.display = '';
        }

        return;
      }

      const editBtn = e.target.closest('.edit-btn');
      if (editBtn) {
        e.stopPropagation();
        const habitId = editBtn.dataset.habitid;
        console.log('Edit clicked ->', habitId);
        if (typeof openEditPopup === 'function') {
          openEditPopup(habitId);
        } else {
          console.warn('openEditPopup(habitId) not found — implement to open your popup.');
        }
        closeAll();
        return;
      }

      const deleteBtn = e.target.closest('.delete-btn');
      if (deleteBtn) {
        e.stopPropagation();
        const habitId = deleteBtn.dataset.habitid;
        console.log('Delete clicked ->', habitId);
        if (confirm('Delete this habit?')) {
          fetch('deleteHabit.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'habit_id=' + encodeURIComponent(habitId)
          }).then(r => r.text()).then(t => {
            console.log('delete response:', t);
            location.reload();
          }).catch(err => console.error('delete error', err));
        }
        closeAll();
        return;
      }

      closeAll();
    } catch (err) {
      console.error('kebab handler error', err);
    }
  });

  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAll(); });

  console.log('kebab dropdown script loaded');
})();

    //ajax for logging habit
document.querySelectorAll('.habit-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        const habitId = this.dataset.habitid;
        const checked = this.checked ? 1 : 0;

        fetch('logHabit.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `habit_id=${habitId}&checked=${checked}`
        })
        .then(res => res.text())
        .then(data => console.log(data))
        .catch(err => console.error(err));
    });
});

</script>

</html>