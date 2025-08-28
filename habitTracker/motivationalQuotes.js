fetch("motivationalQuotes.php")
  .then(response => {
    if (!response.ok) {
      console.error("Quote fetch failed:", response.status, response.statusText);
      throw new Error("Failed to fetch quote");
    }
    return response.json();
  })
  .then(data => {
    if (!data || !data.quote) {
      throw new Error("Quote data is missing");
    }
    document.getElementById("quoteText").innerText = data.quote;
  })
  .catch(error => {
    console.error("Error loading quote:", error);
    document.getElementById("quoteText").innerText = "💡 Stay strong! You got this."; // fallback quote
  });

fetch("habitStreak.php")
  .then(response => {
    if (!response.ok) {
      console.error("Streak fetch failed:", response.status, response.statusText);
      throw new Error("Failed to fetch streak");
    }
    return response.json();
  })
.then(data => {
  if (!data || typeof data.longest_streak === "undefined") {
    throw new Error("Streak data is missing");
  }
  document.getElementById("streakNumber").innerText = data.longest_streak;
})
.catch(error => {
  console.error("Error loading streak:", error);
  document.getElementById("streakNumber").innerText = "0";
  document.getElementById("habitStreak").innerText = "No longest habit streak";
});

