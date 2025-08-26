document.addEventListener("DOMContentLoaded", function () {
    const habitForm = document.getElementById('habit-form');
    const habitIdField = document.getElementById('habit_id');
    const habitNameField = document.getElementById('habit'); 
    const remarksField = document.getElementById('remarks');
    const submitBtn = habitForm.querySelector('button[type="submit"]');
    const popupForm = document.getElementById('popup-form');

window.openEditPopup = function(habitId) {
    fetch(`getHabit.php?habit_id=${habitId}`)
        .then(res => res.json())
        .then(data => {
            habitIdField.value = data.habit_id;
            habitNameField.value = data.habit_name;
            remarksField.value = data.description || "";
            habitForm.querySelectorAll('input[name="repeat[]"]').forEach(cb => {
                cb.checked = data.repeat_days.includes(cb.value);
            });
            submitBtn.textContent = "Update";
            popupForm.style.display = 'flex';
        });
};

});
