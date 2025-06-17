document.addEventListener("DOMContentLoaded", function () {
    const toggles = document.querySelectorAll(".toggle-password");

    toggles.forEach((toggle) => {
        toggle.addEventListener("click", () => {
            const targetId = toggle.dataset.target;
            const input = document.getElementById(targetId);

            if (!input) {
                console.warn(`No input found with ID: ${targetId}`);
                return;
            }

            if (input.type === "password") {
                input.type = "text";
                toggle.textContent = "👁️‍🗨️"; // Hide
            } else {
                input.type = "password";
                toggle.textContent = "👁️"; // Show
            }
        });
    });
});
