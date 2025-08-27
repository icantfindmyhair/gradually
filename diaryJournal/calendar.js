// Start For Time And Greeting Logic
// This waits until the DOM is fully loaded, even if the script is in the <head>
function showGreeting() {
    let now = new Date();
    let hour = now.getHours();
    let greeting = "";

    if (hour >= 5 && hour < 12){
        greeting = "Good Morning";
    }
    else if (hour >= 12 && hour < 17){
        greeting = "Good Afternoon";
    }
    else{
        greeting = "Good Evening";
    }

    // Display greeting in HTML
    let greetingElement = document.getElementById("greeting");
    if(greetingElement){
        greetingElement.innerText = greeting;
    }
    else{
        console.warn("Greeting element not found in DOM");
    }
}

// Wait until the page is fully loaded, then check if target element exists
document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("greeting")) {
        showGreeting(); // auto-trigger the function only when the element is present
    }
});
// End For Time And Greeting Logic

// Start For Calendar User Interface Development
let today = new Date();
let currentMonth = today.getMonth();
let currentYear = today.getFullYear();
let selectedCell = null;

const monthNames = [
    "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
];

function buildCalendar(month, year) {
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    let calendar = '<table>';
    calendar += '<tr><th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th></tr><tr>';

    let dayOfWeek = firstDay;
    let currentDay = 1;

    // Clear previously selected cell
    selectedCell = null;

    // Fill initial empty cells
    for (let i = 0; i < dayOfWeek; i++) {
        calendar += "<td></td>";
    }

    while (currentDay <= daysInMonth) {
        if (dayOfWeek === 7){
            dayOfWeek = 0;
            calendar += "</tr><tr>";
        }

        const dateStr = `${year}-${String(month + 1) . padStart(2, '0')}-${String(currentDay) . padStart(2, '0')}`;
        /* 
        Create formatted data string in YYYY-MM-DD format (commonly used in database and ISO standards
        month + 1: Adjusts the month to be 1-based (because Date.getMonth() returns 0 - 11)
        String(...): Converts the number to a string
        .padStart(2, '0'): Ensure at least 2 digits by adding leading zero if needed
        */

        const isToday = (
            currentDay === today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear()
        );

        const cellClass = isToday ? "today" : "";

        calendar += `<td class="${cellClass}">
                     <a href="#" data-date="${dateStr}" onclick="return false;">${currentDay}</a>
                     </td>`;
        
        currentDay++;
        dayOfWeek++;
    }

    // Fill trailing empty cells
    while (dayOfWeek < 7) {
        calendar += "<td></td>";
        dayOfWeek++;
    }

    calendar += "</tr></table>";

    document.getElementById("calendar").innerHTML = calendar;
    document.getElementById("monthYear").textContent = `${monthNames[month]} ${year}`;

    addEventListeners();
}

function changeMonth(delta) {
    currentMonth += delta;
    if (currentMonth < 0) {
        // Date object months are zero-based
        currentMonth = 11;
        currentYear--;
    }
    else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    buildCalendar(currentMonth, currentYear);
}

function addEventListeners() {
    const links = document.querySelectorAll("#calendar td a");
    links.forEach(link => {
        const td = link.parentElement;

        // Single Click
        link.addEventListener("click", (e) => {
            // Do not do the default action that normally happens when this event is triggered
            e.preventDefault();

            if (selectedCell){
                selectedCell.classList.remove("selected");
            }
            td.classList.add("selected");
            selectedCell = td;
        });

        // Double Click
        link.addEventListener("dblclick", (e) => {
            e.preventDefault();

            const date = link.getAttribute("data-date");
            window.location.href = `writeDiary.php?date=${date}`;
        })
    })
}

document.addEventListener("DOMContentLoaded", function() {
    // Check if calendar container exists on the page
    if (document.getElementById("calendar") && document.getElementById("monthYear")) {
        // Initialize calendar when elements are present
        buildCalendar(currentMonth, currentYear);
    }
});
// End For Calendar User Interface Development