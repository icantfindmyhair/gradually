document.addEventListener("DOMContentLoaded", function () {

    // =================== Mood Picker ===================
    const moodDisplay = document.getElementById('moodDisplay');
    const moodPicker = document.getElementById('moodPicker');
    const selectedEmojiSpan = document.getElementById('selectedEmoji');
    const categoryTabs = document.querySelectorAll('.category-tab');
    const emojiIcons = document.querySelectorAll('.emoji-icon');
    const hiddenMoodInput = document.getElementById('mood_id');

    let moodPickerVisible = false;

    if (moodDisplay && moodPicker) {
        const defaultCategory = "<?= $firstCategory ?>";

        function activateCategoryTab(category) {
            document.querySelectorAll('.emoji-list').forEach(list => {
                list.classList.remove('active');
                if (list.dataset.category === category) list.classList.add('active');
            });

            categoryTabs.forEach(tab => {
                tab.classList.remove('active');
                if (tab.dataset.category === category) tab.classList.add('active');
            });
        }

        moodDisplay.addEventListener('click', function () {
            moodPickerVisible = !moodPickerVisible;
            moodPicker.style.display = moodPickerVisible ? 'block' : 'none';
            if (moodPickerVisible) {
                activateCategoryTab(defaultCategory);
            }
        });

        categoryTabs.forEach(tab => {
            tab.addEventListener('click', function () {
                activateCategoryTab(this.dataset.category);
            });
        });

        emojiIcons.forEach(icon => {
            icon.addEventListener('click', function () {
                const clickedSrc = this.getAttribute('data-emoji-src');
                const clickedId = this.getAttribute('data-emoji-id');
                const existingEmoji = selectedEmojiSpan.querySelector('img');

                if (existingEmoji && existingEmoji.getAttribute('src') === clickedSrc) {
                    selectedEmojiSpan.innerHTML = '';
                    hiddenMoodInput.value = '';
                } else {
                    selectedEmojiSpan.innerHTML = `<img src="${clickedSrc}" width="48" height="48" />`;
                    hiddenMoodInput.value = clickedId;
                }

                moodPicker.style.display = 'none';
                moodPickerVisible = false;
                isDirty = true;
            });
        });
    }

    // =================== Weather Picker ===================
    const weatherDisplay = document.getElementById('weatherDisplay');
    const weatherPicker = document.getElementById('weatherPicker');
    const selectedWeatherSpan = document.getElementById('selectedWeather');
    const weatherIcons = document.querySelectorAll('.weather-icon');
    const hiddenWeatherInput = document.getElementById('weather_id');

    let weatherPickerVisible = false;

    if (weatherDisplay && weatherPicker) {
        weatherDisplay.addEventListener('click', function () {
            weatherPickerVisible = !weatherPickerVisible;
            weatherPicker.style.display = weatherPickerVisible ? 'block' : 'none';
        });

        weatherIcons.forEach(icon => {
            icon.addEventListener('click', function () {
                const weatherSrc = this.getAttribute('data-weather-src');
                const weatherName = this.getAttribute('data-weather-name');
                const weatherId = this.getAttribute('data-weather-id');

                selectedWeatherSpan.innerHTML = `<img src="${weatherSrc}" width="48" height="48" /> ${weatherName}`;
                hiddenWeatherInput.value = weatherId;

                weatherPicker.style.display = 'none';
                weatherPickerVisible = false;
                isDirty = true;
            });
        });
    }

    // =================== Unsaved Changes Handling ===================
    
    document.addEventListener("DOMContentLoaded", function () {
        let isDirty = false;
        let isSubmitting = false;

        const form = document.getElementById('diaryForm');
        const diaryContent = document.getElementById('diary_content');
        const saveButton = document.getElementById('saveBtn');

        // Track changes
        if (diaryContent) {
            diaryContent.addEventListener("input", () => {
                isDirty = true;
            });
        }

        // Add listeners to mood/weather icons if they exist
        document.querySelectorAll('.emoji-icon, .weather-icon').forEach(icon => {
            icon.addEventListener('click', () => {
                isDirty = true;
            });
        });

        // Handle form submission
        if (form) {
            form.addEventListener('submit', function (e) {
                if (isSubmitting) {
                    e.preventDefault(); // Prevent second submission
                    return;
                }

                console.log("Submitting form...");

                isSubmitting = true;
                isDirty = false;
                window.onbeforeunload = null;

                if (saveButton) {
                    saveButton.disabled = true;
                }

                // Do NOT prevent default so that the form submits normally
            });
        }

        // Warn before leaving unsaved
        window.addEventListener("beforeunload", function (e) {
            if (isDirty && !isSubmitting) {
                const confirmationMessage = "You have unsaved changes. Save, Discard, or Cancel?";
                (e || window.event).returnValue = confirmationMessage;
                return confirmationMessage;
            }
        });
    });
});
