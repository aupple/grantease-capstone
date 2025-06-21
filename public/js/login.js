// GrantEase JS - Password Toggle
document.addEventListener("DOMContentLoaded", function () {
    const toggles = document.querySelectorAll(".toggle-password");

    toggles.forEach((toggle) => {
        toggle.addEventListener("click", () => {
            const input = document.getElementById(toggle.dataset.target);
            if (!input) return;

            if (input.type === "password") {
                input.type = "text";
                toggle.textContent = "👁‍🗨"; 
            } else {
                input.type = "password";
                toggle.textContent = "👁️"; 
            }
        });
    });
});
