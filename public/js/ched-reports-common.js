// Common functionality for all CHED reports
document.addEventListener("DOMContentLoaded", function () {
    // Current active view
    let currentView = "personal";

    // Tab switching
    const viewTabs = document.querySelectorAll(".view-tab");
    const viewFields = document.querySelectorAll(".view-fields");
    const viewTables = document.querySelectorAll(".view-table");

    viewTabs.forEach((tab) => {
        tab.addEventListener("click", function () {
            const viewName = this.id.replace("view", "").toLowerCase();

            // Update active tab styling
            viewTabs.forEach((t) => {
                t.classList.remove("bg-blue-600", "text-white");
                t.classList.add(
                    "bg-gray-200",
                    "text-gray-700",
                    "hover:bg-gray-300"
                );
            });
            this.classList.remove(
                "bg-gray-200",
                "text-gray-700",
                "hover:bg-gray-300"
            );
            this.classList.add("bg-blue-600", "text-white");

            // Show corresponding fields and table
            viewFields.forEach((field) => field.classList.add("hidden"));
            viewTables.forEach((table) => table.classList.add("hidden"));

            document
                .getElementById(`fields-${viewName}`)
                .classList.remove("hidden");
            document
                .getElementById(`table-${viewName}`)
                .classList.remove("hidden");

            currentView = viewName;
        });
    });

    // Column visibility toggle
    const fieldChecks = document.querySelectorAll(".field-check");

    fieldChecks.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            const colName = this.dataset.col;
            const isChecked = this.checked;

            const columns = document.querySelectorAll(`.col-${colName}`);
            columns.forEach((col) => {
                if (isChecked) {
                    col.classList.remove("col-hidden");
                } else {
                    col.classList.add("col-hidden");
                }
            });
        });
    });

    // Print functionality
    document.getElementById("printBtn").addEventListener("click", function () {
        const printTitles = {
            personal: "CHED Monitoring Scholars - Personal Information",
            gradereport: "CHED Monitoring Scholars - SIKAP DHEI Grade Report",
            enrollment:
                "CHED Monitoring Scholars - SIKAP DHEI Enrollment Report",
            continuing:
                "CHED Monitoring Scholars - SIKAP Continuing Eligibility Report",
        };

        const originalTitle = document.title;
        document.title = printTitles[currentView] || originalTitle;
        window.print();
        document.title = originalTitle;
    });

    // Reset columns
    document.getElementById("resetCols").addEventListener("click", function () {
        const currentFieldChecks = document.querySelectorAll(
            `.field-check[data-view="${currentView}"]`
        );

        currentFieldChecks.forEach((checkbox) => {
            checkbox.checked = true;
            const colName = checkbox.dataset.col;
            const columns = document.querySelectorAll(`.col-${colName}`);
            columns.forEach((col) => {
                col.classList.remove("col-hidden");
            });
        });

        showFeedback("All columns have been reset and are now visible.");
    });

    // Helper function - make it global so other scripts can use it
    window.showFeedback = function (message) {
        const existingFeedback = document.getElementById("feedback-message");
        if (existingFeedback) {
            existingFeedback.remove();
        }

        const feedback = document.createElement("div");
        feedback.id = "feedback-message";
        feedback.className =
            "fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300";
        feedback.textContent = message;

        document.body.appendChild(feedback);

        setTimeout(() => {
            feedback.style.opacity = "0";
            setTimeout(() => feedback.remove(), 300);
        }, 3000);
    };

    // Initialize columns
    document.querySelectorAll(".field-check").forEach((checkbox) => {
        if (checkbox.checked) {
            const colName = checkbox.dataset.col;
            const columns = document.querySelectorAll(`.col-${colName}`);
            columns.forEach((col) => {
                col.classList.remove("col-hidden");
            });
        }
    });

    // Keyboard shortcuts
    document.addEventListener("keydown", function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === "p") {
            e.preventDefault();
            document.getElementById("printBtn").click();
        }

        if ((e.ctrlKey || e.metaKey) && e.key === "r") {
            e.preventDefault();
            document.getElementById("resetCols").click();
        }
    });

    console.log(
        "CHED Reports Common Script Loaded - Initial view:",
        currentView
    );
});
