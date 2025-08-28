document.addEventListener("DOMContentLoaded", function () {
    const habitForm = document.getElementById('habit-form');
    const habitIdField = document.getElementById('habit_id');
    const habitNameField = document.getElementById('habit'); 
    const remarksField = document.getElementById('remarks');
    const submitBtn = habitForm.querySelector('button[type="submit"]');
    const popupForm = document.getElementById('popup-form');

    window.openEditPopup = function(habitId) {
        try {
            fetch(`getHabit.php?habit_id=${habitId}`)
                .then(res => {
                    if (!res.ok) {
                        console.error("Server responded with error:", res.status, res.statusText);
                        throw new Error("Failed to fetch habit data.");
                    }
                    return res.json();
                })
                .then(data => {
                    if (!data || !data.habit_id) {
                        throw new Error("Invalid habit data received.");
                    }

                    habitIdField.value = data.habit_id;
                    habitNameField.value = data.habit_name || "";
                    remarksField.value = data.description || "";

                    habitForm.querySelectorAll('input[name="repeat[]"]').forEach(cb => {
                        cb.checked = data.repeat_days && data.repeat_days.includes(cb.value);
                    });

                    submitBtn.textContent = "Update";
                    popupForm.style.display = 'flex';
                })
                .catch(error => {
                    console.error("Error fetching habit details:", error);
                    alert("Oops! Could not load the habit details. Please try again.");
                });
        } catch (error) {
            console.error("Unexpected error in openEditPopup:", error);
            alert("An unexpected error occurred.");
        }
    };
});
