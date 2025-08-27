document.addEventListener("DOMContentLoaded", function () {
    const graphContainer = document.getElementById("graphContainer");

    let habits = [];
    let dates = [];

    const defaultDays = 30;

    function fetchData(days = defaultDays) {
        return fetch(`habitGraph.php?days=${days}`)
            .then(res => res.json())
            .then(data => {
                habits = data.habits;
                dates = data.dates;
            });
    }

    function renderHabitGraph() {
        let html = `<h3 style="margin-top: 2rem; text-align: center; font-size: 22px;">Streaks for The Past ${defaultDays} Days</h3>`;

        html += `<div class="habit-row" style="margin-top: 1rem; align-items:center;">`;
        html += `<span class="habit-name" style="width:100px;"></span>`;
        html += `<div style="
            display: grid;
            grid-template-columns: repeat(${dates.length}, 30px);
            gap: 6px;
            flex:1;
        ">`;

    dates.forEach(dateStr => {
        const dateObj = new Date(dateStr);
        const day = dateObj.getDate();
        const month = dateObj.toLocaleString('default', { month: 'short' });
        html += `<span style="
            font-size:15px;
            text-align:center;
            display:block;
        ">${day} ${month}</span>`;
    });

        html += `</div></div>`;

        html += `<div class="heatmap habits">`;
        habits.forEach(habit => {
            html += `<div class="habit-row" style="align-items:center;">`;
            html += `<span class="habit-name" style="width:100px;">${habit.name}</span>`;
            html += `<div style="
                display: grid;
                grid-template-columns: repeat(${dates.length}, 30px);
                gap: 6px;
                flex:1;
            ">`;
            dates.forEach(date => {
                let done = habit.records[date] || 0;
                let intensity = done ? "done" : "miss";
                html += `<div class="cell ${intensity}" title="${habit.name} on ${date}: ${done ? '✓' : '✗'}"></div>`;
            });
            html += `</div></div>`;
        });
        html += `</div>`;

        graphContainer.innerHTML = html;
    }

    fetchData().then(renderHabitGraph);
});
