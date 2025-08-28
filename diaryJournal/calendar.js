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

        // Single Click: Highlight selected date
        link.addEventListener("click", (e) => {
            // Do not do the default action that normally happens when this event is triggered
            e.preventDefault();

            if (selectedCell){
                selectedCell.classList.remove("selected");
            }
            td.classList.add("selected");
            selectedCell = td;
        });

        // Double Click: Check the appearance of diary content and direct through view or write accordingly
        /* 
        async -> asynchronous
        Can use await inside function
        The function returns promise automatically
        Allow to pause the function execution at certain points (awit) and wait for asynchronous operations to complete
        */
       // await pause the execution inside an async function until the promise resolves
        link.addEventListener("dblclick", async (e) => {
            e.preventDefault();

            const date = link.getAttribute("data-date");

            try {
                const response = await fetch(`checkDiary.php?date=${encodeURIComponent(date)}`);
                // Waits for the server to respond

                // const response = await fetch(url);
                // waits for the HTTP request to complete

                // fetch(...) is a built-in function used to make HTTP requests (like GET or POST)
                // Return a Promise that resolves to a Response object

                // fetch(`checkDiary.php?date=${encodeURIComponent(date)}`)
                // Make a GET request to checkDiary.php with a query parameter date=...

                // encodeURIComponent(...): safely encode special characters in URL
                // Prevents issues when characters like /, ?, &, = are in the URL
                const data = await response.json();  // This is the JSON response, e.g., {exists: true/false}
                // Waits to parse the response body into a JavaScript object

                // .jason(): call on the response object from fetch
                // return a promise that resolves to the parsed JavaScript object from the JSON response

                if (data.exists) {
                    const confirmView = await showModal("Do you want to look on the diary written before?");
                    if (confirmView) {
                        window.location.href = `viewDiary.php?date=${encodeURIComponent(date)}`;
                    }
                }
                else {
                    const confirmWrite = await showModal("Would you like to write something in your diary?");
                    if (confirmWrite) {
                        window.location.href = `writeDiary.php?date=${encodeURIComponent(date)}`;
                    }
                }
            } catch (error) {
                console.error("error checking diary: ", error);
                showModal("An error occured while checking the diary. ");
            }
        });
    });
}

function showModal(message) {
    return new Promise((resolve) => {
        const modal = document.getElementById("customModal");
        const modalMessage = document.getElementById("modalMessage");
        const confirmBtn = document.getElementById("modalConfirm");
        const cancelBtn = document.getElementById("modalCancel");

        modalMessage.textContent = message;
        modal.classList.remove("hidden");

        const cleanUp = () => {
            modal.classList.add("hidden");
            confirmBtn.removeEventListener("click", onConfirm);
            cancelBtn.removeEventListener("click", onCancel);
        };

        const onConfirm = () => {
            cleanUp();
            resolve(true);
        };

        const onCancel = () => {
            cleanUp();
            resolve(false);
        };

        confirmBtn.addEventListener("click", onConfirm);
        cancelBtn.addEventListener("click", onCancel);
    });
}

document.addEventListener("DOMContentLoaded", function() {
    // Check if calendar container exists on the page
    if (document.getElementById("calendar") && document.getElementById("monthYear")) {
        // Initialize calendar when elements are present
        buildCalendar(currentMonth, currentYear);
    }
});
// End For Calendar User Interface Development