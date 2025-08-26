function createDonutChart(ctx, completed, remaining, line1, line2) {
  const total = completed + remaining;
  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["Completed", "Remaining"],
      datasets: [{
        data: [completed, remaining],
        backgroundColor: ["#36A2EB", "#E0E0E0"],
        cutout: "70%"
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } }
    },
    plugins: [{
      id: "centerText",
      beforeDraw(chart) {
        const { width, height, ctx } = chart;
        ctx.save();
        ctx.font = "bold 16px Zen Maru Gothic";
        ctx.fillStyle = "#333";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        ctx.fillText(line1, width / 2, height / 2 - 10);
        ctx.font = "bold 12px Zen Maru Gothic";
        ctx.fillStyle = "#666";
        ctx.fillText(line2, width / 2, height / 2 + 12);
        ctx.restore();
      }
    }]
  });
}

createDonutChart(document.getElementById("habitChart1"), 2, 3, "2/5", "Habits");
createDonutChart(document.getElementById("habitChart2"), 4, 1, "4/5", "Completed");
createDonutChart(document.getElementById("habitChart3"), 1, 5, "1/6", "In Progress");
