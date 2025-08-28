<?php

// to list all habits when user want to view all habit
define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH.'/database.php';

$sql = 'SELECT * FROM habit_type ORDER BY habit_name ASC';
$result = mysqli_query($con, $sql);

echo '<ul class="todo-items">';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<li>';
    echo '<div class="habit-left">';
    echo '<label>'.htmlspecialchars($row['habit_name']).'</label>';
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
?>
<script src="habitForm.js"></script>
<script src="habitMenu.js"></script>
<script src="addHabit.js"></script>


