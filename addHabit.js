document.addEventListener("DOMContentLoaded", function () {
    const openFormBtn = document.getElementById('open-form');
    const closeFormBtn = document.getElementById('close-form');
    const popupForm = document.getElementById('popup-form');

    const habitForm = document.getElementById('habit-form');
    const habitIdField = document.getElementById('habit_id');
    const submitBtn = habitForm.querySelector('button[type="submit"]');

    openFormBtn.addEventListener('click', () => {
        habitForm.reset();
        habitIdField.value = "";
        submitBtn.textContent = "Add";
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
});
