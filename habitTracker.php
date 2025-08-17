<!DOCTYPE html>
<html lang="en">

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

            <ul class="todo-items">
                <li>round check list / click to strike through when completed, maybe sound effect?</li>
                <li>different font size for hobby name and description</li>
                <li>example</li>
            </ul>
        </div>

        <!-- Popup Form -->
        <div class="popup-form" id="popup-form">
            <div class="popup-content">
                <div class="popup-header">
                    <h3>Add A New Habit</h3>
                    <span class="close-btn" id="close-form">&times;</span>
                </div>
                <form>
                    <label for="habit" class="field-label">Habit Name:</label>
                    <input type="text" id="habit" name="habit" required><br>

                    <label for="remarks" class="field-label">Remarks:</label>
                    <input type="text" id="remarks" name="remarks"><br>

                    <label class="field-label">Repeat:</label>
                    <ul class="repeat-list">
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="monday">
                                <span class="repeat-text">Monday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="tuesday">
                                <span class="repeat-text">Tuesday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="wednesday">
                                <span class="repeat-text">Wednesday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="thursday">
                                <span class="repeat-text">Thursday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="friday">
                                <span class="repeat-text">Friday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="saturday">
                                <span class="repeat-text">Saturday</span>
                                <span class="repeat-tick" aria-hidden="true"></span>
                            </label>
                        </li>
                        <li>
                            <label class="repeat-row">
                                <input type="checkbox" name="repeat[]" value="sunday">
                                <span class="repeat-text">Sunday</span>
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
    //pop up
    const openFormBtn = document.getElementById('open-form');
    const closeFormBtn = document.getElementById('close-form');
    const popupForm = document.getElementById('popup-form');
    // open
    document.getElementById('open-form').addEventListener('click', () => {
        document.getElementById('popup-form').classList.add('is-open');
    });
    // close (×)
    document.getElementById('close-form').addEventListener('click', () => {
        document.getElementById('popup-form').classList.remove('is-open');
    });
    // Open popup
    openFormBtn.addEventListener('click', () => {
        popupForm.style.display = 'flex';
    });
    // Close popup
    closeFormBtn.addEventListener('click', () => {
        popupForm.style.display = 'none';
    });
    // Close when clicking outside the popup box
    window.addEventListener('click', (e) => {
        if (e.target === popupForm) {
            popupForm.style.display = 'none';
        }
    });
</script>

</html>