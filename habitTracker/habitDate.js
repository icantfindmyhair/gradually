document.addEventListener("DOMContentLoaded", function() {
    try {
        const today = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };

        const dateElement = document.getElementById("today-date");

        if (!dateElement) {
            throw new Error("Element with ID 'today-date' not found.");
        }

        dateElement.textContent = today.toLocaleDateString(undefined, options);

    } catch (error) {
        console.error("Error displaying today's date:", error);
        alert("Oops! Something went wrong while loading the date."); 
    }
});
