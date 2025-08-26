document.addEventListener("DOMContentLoaded", function() {
    const today = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById("today-date").textContent = today.toLocaleDateString(undefined, options);
});
