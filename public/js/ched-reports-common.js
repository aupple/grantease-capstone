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

            // Store current view
            sessionStorage.setItem("currentView", viewName);
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

            // Save state to localStorage
            saveColumnState(currentView, colName, isChecked);
        });
    });

    // Print/Export functionality
    document.getElementById("printBtn").addEventListener("click", function () {
        // Check if current view has Excel template
        if (currentView === "personal") {
            // Personal Information - Open printable page in new window
            printPersonalInformation();
            return;
        }

        // For Grade Report, Enrollment, and Continuing - Export to Excel
        exportToExcel();
    });

    // Print Personal Information in new window
    function printPersonalInformation() {
        // Get filter values
        const semester =
            document.querySelector('select[name="semester"]')?.value || "";
        const academicYear =
            document.querySelector('select[name="academic_year"]')?.value || "";

        // Get visible columns
        const visibleColumns = [];
        document
            .querySelectorAll('.field-check[data-view="personal"]:checked')
            .forEach((checkbox) => {
                visibleColumns.push(checkbox.dataset.col);
            });

        // Build URL with parameters
        let url = "/admin/reports/ched-monitoring/print-personal?";
        const params = new URLSearchParams();

        if (semester) params.append("semester", semester);
        if (academicYear) params.append("academic_year", academicYear);
        params.append("columns", JSON.stringify(visibleColumns));

        url += params.toString();

        // Open in new window
        window.open(url, "_blank");
    }

    // Export to Excel function
    function exportToExcel() {
        // Get filter values
        const semester =
            document.querySelector('select[name="semester"]')?.value || "";
        const academicYear =
            document.querySelector('select[name="academic_year"]')?.value || "";

        // Get hidden columns for current view
        const hiddenColumns = [];
        document
            .querySelectorAll(
                `.field-check[data-view="${currentView}"]:not(:checked)`
            )
            .forEach((checkbox) => {
                hiddenColumns.push(checkbox.dataset.col);
            });

        // Show loading state
        const printBtn = document.getElementById("printBtn");
        const originalText = printBtn.textContent;
        printBtn.disabled = true;
        printBtn.textContent = "Generating Excel...";

        // Create form and submit
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "/admin/reports/ched-monitoring/export-excel";
        form.style.display = "none";

        // Add CSRF token
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        )?.content;
        if (csrfToken) {
            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }

        // Add report type
        const reportTypeInput = document.createElement("input");
        reportTypeInput.type = "hidden";
        reportTypeInput.name = "report_type";
        reportTypeInput.value = currentView;
        form.appendChild(reportTypeInput);

        // Add filters
        if (semester) {
            const semesterInput = document.createElement("input");
            semesterInput.type = "hidden";
            semesterInput.name = "semester";
            semesterInput.value = semester;
            form.appendChild(semesterInput);
        }

        if (academicYear) {
            const yearInput = document.createElement("input");
            yearInput.type = "hidden";
            yearInput.name = "academic_year";
            yearInput.value = academicYear;
            form.appendChild(yearInput);
        }

        // Add hidden columns
        hiddenColumns.forEach((col) => {
            const colInput = document.createElement("input");
            colInput.type = "hidden";
            colInput.name = "hidden_columns[]";
            colInput.value = col;
            form.appendChild(colInput);
        });

        document.body.appendChild(form);
        form.submit();

        // Reset button state after delay
        setTimeout(() => {
            printBtn.disabled = false;
            printBtn.textContent = originalText;
            if (document.body.contains(form)) {
                document.body.removeChild(form);
            }
        }, 3000);
    }

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

        // Clear saved states for current view
        clearColumnStates(currentView);

        showFeedback("All columns have been reset and are now visible.");
    });

    // Save column state to localStorage
    function saveColumnState(view, col, visible) {
        const states = JSON.parse(localStorage.getItem("columnStates") || "{}");
        const key = `${view}_${col}`;
        states[key] = visible;
        localStorage.setItem("columnStates", JSON.stringify(states));
    }

    // Clear column states for a view
    function clearColumnStates(view) {
        const states = JSON.parse(localStorage.getItem("columnStates") || "{}");
        Object.keys(states).forEach((key) => {
            if (key.startsWith(`${view}_`)) {
                delete states[key];
            }
        });
        localStorage.setItem("columnStates", JSON.stringify(states));
    }

    // Load saved column states on page load
    function loadColumnStates() {
        const states = JSON.parse(localStorage.getItem("columnStates") || "{}");

        Object.keys(states).forEach((key) => {
            const visible = states[key];
            const [view, col] = key.split("_");

            const checkbox = document.querySelector(
                `.field-check[data-view="${view}"][data-col="${col}"]`
            );

            if (checkbox) {
                checkbox.checked = visible;
                const columns = document.querySelectorAll(`.col-${col}`);
                columns.forEach((column) => {
                    if (visible) {
                        column.classList.remove("col-hidden");
                    } else {
                        column.classList.add("col-hidden");
                    }
                });
            }
        });
    }

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

    // Load saved column states
    loadColumnStates();

    // Restore last view if exists
    const lastView = sessionStorage.getItem("currentView");
    if (lastView && lastView !== "personal") {
        const tab = document.getElementById(
            `view${lastView.charAt(0).toUpperCase() + lastView.slice(1)}`
        );
        if (tab) {
            tab.click();
        }
    }

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
