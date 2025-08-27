
document.addEventListener('DOMContentLoaded', function () {
  const graphContainer = document.getElementById('graphContainer');
  const defaultDays = 30;

  function fetchData(days = defaultDays) {
    return fetch(`habitGraph.php?days=${days}&_=${Date.now()}`)
      .then(r => {
        if (!r.ok) throw new Error('Network response not ok');
        return r.json();
      });
  }

  function renderHabitGraph(data) {
    const dates = data.dates;
    const habits = data.habits;

    let html = `<h3 style="margin-top:2rem; text-align:center; font-size:22px;">Streaks for The Past ${dates.length} Days</h3>`;

    html += `<div class="habit-row" style="margin-top:1rem; align-items:center;">`;
    html += `<span class="habit-name" style="width:120px;"></span>`;
    html += `<div style="
                display:grid;
                grid-template-columns: repeat(${dates.length}, 30px);
                gap:6px;
                flex:1;
            ">`;
    dates.forEach(dateStr => {
      const dateObj = new Date(dateStr + 'T00:00:00');
      const day = dateObj.getDate();
      const mon = dateObj.toLocaleString(undefined, { month: 'short' });
      html += `<span style="font-size:12px; text-align:center; display:block;">${day} ${mon}</span>`;
    });
    html += `</div></div>`;

    // habit rows
    html += `<div class="heatmap habits">`;
    habits.forEach(habit => {
      html += `<div class="habit-row" style="align-items:center;">`;
      html += `<span class="habit-name" style="width:120px; font-size:13px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${escapeHtml(habit.name)}</span>`;
      html += `<div style="
                  display:grid;
                  grid-template-columns: repeat(${dates.length}, 30px);
                  gap:6px;
                  flex:1;
              ">`;
      dates.forEach(date => {
        const done = habit.records[date] ? 1 : 0;
        const cls = done ? 'done' : 'miss';
        html += `<div class="cell ${cls}" title="${escapeHtml(habit.name)} on ${date}: ${done ? '✓' : '✗'}"></div>`;
      });
      html += `</div></div>`;
    });
    html += `</div>`;

    graphContainer.innerHTML = html;
  }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"]/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c];
    });
  }

  window.refreshHabitGraph = function (days = defaultDays) {
    return fetchData(days)
      .then(data => {
        renderHabitGraph(data);
        return data;
      })
      .catch(err => {
        console.error('Failed to load habit graph:', err);
      });
  };

  window.refreshHabitGraph();

  setInterval(() => {
    window.refreshHabitGraph();
  }, 60 * 1000);
});
