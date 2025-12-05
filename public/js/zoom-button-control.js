/**
 * Zoom Button Time-Based Control
 * 
 * Automatically enables/disables Zoom meeting buttons based on session timing.
 * - Buttons are disabled before session starts (with 10-minute buffer)
 * - Buttons are enabled during session
 * - Buttons are disabled after session ends
 * - Shows countdown timer when session hasn't started yet
 */

document.addEventListener('DOMContentLoaded', function() {
    const BUFFER_MINUTES = 10; // Enable button 10 minutes before class starts
    const UPDATE_INTERVAL = 30000; // Update every 30 seconds

    // Find all time-restricted Zoom buttons
    const buttons = document.querySelectorAll('.zoom-button.time-restricted');

    if (buttons.length === 0) {
        return; // No buttons to control
    }

    /**
     * Update the state of all Zoom buttons based on current time
     */
    function updateButtonStates() {
        const now = new Date();

        buttons.forEach(button => {
            const startTimeISO = button.dataset.startTime;
            const duration = parseInt(button.dataset.duration) || 90;

            // Skip if no start time data
            if (!startTimeISO) {
                enableButton(button);
                return;
            }

            try {
                const startTime = new Date(startTimeISO);
                const endTime = new Date(startTime.getTime() + duration * 60000);
                const startTimeWithBuffer = new Date(startTime.getTime() - BUFFER_MINUTES * 60000);

                const isBeforeBuffer = now < startTimeWithBuffer;
                const isAfterEnd = now >= endTime;
                const isDuringSession = now >= startTimeWithBuffer && now < endTime;

                if (isBeforeBuffer) {
                    // Session hasn't started yet - disable with countdown
                    disableButtonWithCountdown(button, startTimeWithBuffer, now);
                } else if (isAfterEnd) {
                    // Session has ended - disable permanently
                    disableButtonEnded(button);
                } else if (isDuringSession) {
                    // Session is active or within buffer - enable
                    enableButton(button);
                }
            } catch (error) {
                console.error('Error parsing date for Zoom button:', error);
                enableButton(button); // Fallback to enabled state
            }
        });
    }

    /**
     * Disable button and show countdown
     */
    function disableButtonWithCountdown(button, startTimeWithBuffer, now) {
        button.classList.add('disabled');
        button.style.pointerEvents = 'none';
        button.style.opacity = '0.5';
        button.style.background = '#cbd5e1';
        button.style.cursor = 'not-allowed';

        // Calculate time until button becomes available
        const minutesUntilStart = Math.ceil((startTimeWithBuffer - now) / 1000 / 60);
        const countdownText = button.querySelector('.countdown-text');
        
        if (countdownText) {
            countdownText.style.display = 'inline';
            
            if (minutesUntilStart > 60) {
                const hours = Math.floor(minutesUntilStart / 60);
                const mins = minutesUntilStart % 60;
                countdownText.textContent = `(${hours}j ${mins}m lagi)`;
            } else {
                countdownText.textContent = `(${minutesUntilStart} menit lagi)`;
            }
        }

        // Optional: Add lock icon
        const buttonText = button.querySelector('.button-text');
        if (buttonText && !buttonText.textContent.includes('🔒')) {
            buttonText.textContent = '🔒 ' + buttonText.textContent;
        }
    }

    /**
     * Disable button for ended session
     */
    function disableButtonEnded(button) {
        button.classList.add('disabled', 'ended');
        button.style.pointerEvents = 'none';
        button.style.opacity = '0.5';
        button.style.background = '#94a3b8';
        button.style.cursor = 'not-allowed';

        const buttonText = button.querySelector('.button-text');
        if (buttonText) {
            buttonText.textContent = 'Sesi Telah Berakhir';
        }

        const countdownText = button.querySelector('.countdown-text');
        if (countdownText) {
            countdownText.style.display = 'none';
        }
    }

    /**
     * Enable button for active session
     */
    function enableButton(button) {
        button.classList.remove('disabled', 'ended');
        button.style.pointerEvents = 'auto';
        button.style.opacity = '1';
        button.style.cursor = 'pointer';

        // Restore original color
        if (button.classList.contains('btn-primary')) {
            button.style.background = '#2d8cff';
        } else {
            button.style.background = '#2d8cff';
        }

        // Hide countdown
        const countdownText = button.querySelector('.countdown-text');
        if (countdownText) {
            countdownText.style.display = 'none';
        }

        // Remove lock icon from button text
        const buttonText = button.querySelector('.button-text');
        if (buttonText && buttonText.textContent.includes('🔒')) {
            buttonText.textContent = buttonText.textContent.replace('🔒 ', '');
        }
    }

    // Initial update
    updateButtonStates();

    // Update every 30 seconds to keep buttons in sync
    setInterval(updateButtonStates, UPDATE_INTERVAL);

    // Also update when page becomes visible again (user switches back to tab)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            updateButtonStates();
        }
    });
});
