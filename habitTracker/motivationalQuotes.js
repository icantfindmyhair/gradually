fetch("motivationalQuotes.php")
  .then(r => r.json())
  .then(data => {
    document.getElementById("quoteText").innerText = data.quote;
  });

fetch("habitStreak.php")
  .then(r => r.json())
  .then(data => {
    document.getElementById("streakNumber").innerText = data.longest_streak;
  });