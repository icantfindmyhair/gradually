document.getElementById("viewHabitsBtn").addEventListener("click", function() {
  const modal = document.getElementById("habitModal");
  const container = document.getElementById("allHabitsContainer");

  modal.style.display = "block";

  fetch("habitListAll.php")
    .then(response => {
      if (!response.ok) {
        console.error("Server error:", response.status, response.statusText);
        throw new Error("Failed to load habits from server.");
      }
      return response.text();
    })
    .then(html => {
      if (!html || html.trim() === "") {
        throw new Error("Empty response received from server.");
      }
      container.innerHTML = html;
    })
    .catch(error => {
      console.error("Error fetching habit list:", error);
      container.innerHTML = "<p style='color:red;'>⚠ Unable to load habits. Please try again later.</p>";
    });
});

document.querySelector(".close").addEventListener("click", function() {
  document.getElementById("habitModal").style.display = "none";
});

window.onclick = function(event) {
  const modal = document.getElementById("habitModal");
  if (event.target === modal) {
    modal.style.display = "none";
  }
};
