function createDonutChart(ctx, completed, remaining, mainText, subText, labelText) {
  const centerText = {
    id: "centerText",
    beforeDraw(chart) {
      const { ctx, chartArea: { width, height } } = chart;
      ctx.save();

      ctx.textAlign = "center";
      ctx.textBaseline = "middle";

      // First line (big main text, e.g. 3/5)
      ctx.font = "bold 16px Zen Maru Gothic";
      ctx.fillStyle = "#000";
      ctx.fillText(mainText, width / 2, height / 2 - 15);

      // Second line (always says Completed)
      ctx.font = "12px Zen Maru Gothic";
      ctx.fillText("Completed", width / 2, height / 2 + 2);

      // Third line (context, e.g. Today / This Week / This Month)
      ctx.font = "12px Zen Maru Gothic";
      ctx.fillText(labelText, width / 2, height / 2 + 18);

      ctx.restore();
    }
  };

  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["Completed", "Remaining"],
      datasets: [{
        data: [completed, remaining],
        backgroundColor: ["#2B3C48ff", "#E0E0E0"],
        cutout: "70%"
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: { enabled: true }
      }
    },
    plugins: [centerText]
  });
}
fetch("chart.php")
  .then(r => r.json())
  .then(data => {
    createDonutChart(
      document.getElementById("habitChart1"),
      data.today.completed, data.today.remaining,
      `${data.today.completed}/${data.today.completed + data.today.remaining}`,
      "Completed",
      "Today"
    );

    createDonutChart(
      document.getElementById("habitChart2"),
      data.week.completed, data.week.remaining,
      `${data.week.completed}/${data.week.completed + data.week.remaining}`,
      "Completed",
      "This Week"
    );

    createDonutChart(
      document.getElementById("habitChart3"),
      data.month.completed, data.month.remaining,
      `${data.month.completed}/${data.month.completed + data.month.remaining}`,
      "Completed",
      "This Month"
    );
  });
