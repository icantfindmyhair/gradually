function openMenu() {
    document.getElementById("sideMenu").style.width = "250px";
}

function closeMenu() {
    document.getElementById("sideMenu").style.width = "0";
}

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".hamburger").forEach(function(btn) {
        btn.addEventListener("click", openMenu);
    });
});
