document.getElementById("viewHabitsBtn").addEventListener("click", function() {
  document.getElementById("habitModal").style.display = "block";

  fetch("habitListAll.php")
    .then(response => response.text())
    .then(html => {
      document.getElementById("allHabitsContainer").innerHTML = html;
    });
});

document.querySelector(".close").addEventListener("click", function() {
  document.getElementById("habitModal").style.display = "none";
});

window.onclick = function(event) {
  if (event.target === document.getElementById("habitModal")) {
    document.getElementById("habitModal").style.display = "none";
  }
};
