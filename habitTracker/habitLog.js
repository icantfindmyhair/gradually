document.querySelectorAll('.habit-checkbox').forEach(cb => {
    cb.addEventListener('change', function () {
        const habitId = this.dataset.habitid;
        const checked = this.checked ? 1 : 0;

        fetch('logHabit.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `habit_id=${habitId}&checked=${checked}`
        })
            .then(res => res.text())
            .then(data => {
                console.log(data);
                if (window.refreshHabitGraph) window.refreshHabitGraph();
            })
            .catch(err => console.error(err));

    });
});
