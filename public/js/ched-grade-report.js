// Grade Report Edit Functionality
document.addEventListener("DOMContentLoaded", function () {
    const gradeReportTable = document.querySelector("#table-gradereport tbody");

    if (!gradeReportTable) {
        console.error("Grade report table not found");
        return;
    }

    console.log("Grade Report Edit Script Loaded");

    // Use event delegation - attach to tbody instead of individual buttons
    gradeReportTable.addEventListener("click", function (e) {
        // Handle Edit button click
        if (e.target.classList.contains("edit-btn")) {
            const row = e.target.closest("tr");
            enterEditMode(row);
        }

        // Handle Cancel button click
        if (e.target.classList.contains("cancel-btn")) {
            const row = e.target.closest("tr");
            exitEditMode(row);
        }

        // Handle Save button click
        if (e.target.classList.contains("save-btn")) {
            const row = e.target.closest("tr");
            saveGradeReport(row);
        }
    });

    function enterEditMode(row) {
        console.log("Entering edit mode for row:", row);

        // Store original values before editing
        const inputs = {
            degree_program: row.querySelector('input[name="degree_program"]'),
            enrolled_subjects: row.querySelector(
                'input[name="enrolled_subjects"]'
            ),
            subjects_passed: row.querySelector('input[name="subjects_passed"]'),
            incomplete_grades: row.querySelector(
                'input[name="incomplete_grades"]'
            ),
            subjects_failed: row.querySelector('input[name="subjects_failed"]'),
            no_grades: row.querySelector('input[name="no_grades"]'),
            not_credited_subjects: row.querySelector(
                'input[name="not_credited_subjects"]'
            ),
            status: row.querySelector('input[name="status"]'),
            gpa: row.querySelector('input[name="gpa"]'),
            remarks: row.querySelector('textarea[name="remarks"]'),
        };

        // Store original values in dataset
        const originalValues = {};
        Object.keys(inputs).forEach((key) => {
            if (inputs[key]) {
                originalValues[key] = inputs[key].value;
            }
        });
        row.dataset.originalValues = JSON.stringify(originalValues);

        // Hide view mode, show edit mode
        row.querySelectorAll(".view-mode").forEach((el) =>
            el.classList.add("hidden")
        );
        row.querySelectorAll(".edit-mode").forEach((el) =>
            el.classList.remove("hidden")
        );
    }

    function exitEditMode(row) {
        console.log("Exiting edit mode for row:", row);

        // Restore original values if they were stored
        if (row.dataset.originalValues) {
            const originalValues = JSON.parse(row.dataset.originalValues);

            Object.keys(originalValues).forEach((key) => {
                const input = row.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = originalValues[key];
                }
            });
        }

        // Show view mode, hide edit mode
        row.querySelectorAll(".edit-mode").forEach((el) =>
            el.classList.add("hidden")
        );
        row.querySelectorAll(".view-mode").forEach((el) =>
            el.classList.remove("hidden")
        );
    }

    function saveGradeReport(row) {
        const scholarId = row.dataset.scholarId;

        if (!scholarId) {
            alert("Error: Scholar ID not found");
            console.error("Scholar ID missing from row:", row);
            return;
        }

        console.log("Saving grade report for scholar ID:", scholarId);

        const formData = {
            degree_program:
                row.querySelector('[name="degree_program"]')?.value || "",
            enrolled_subjects:
                row.querySelector('[name="enrolled_subjects"]')?.value || "",
            subjects_passed:
                row.querySelector('[name="subjects_passed"]')?.value || "",
            incomplete_grades:
                row.querySelector('[name="incomplete_grades"]')?.value || "",
            subjects_failed:
                row.querySelector('[name="subjects_failed"]')?.value || "",
            no_grades: row.querySelector('[name="no_grades"]')?.value || "",
            not_credited_subjects:
                row.querySelector('[name="not_credited_subjects"]')?.value ||
                "",
            status: row.querySelector('[name="status"]')?.value || "",
            gpa: row.querySelector('[name="gpa"]')?.value || "",
            remarks: row.querySelector('[name="remarks"]')?.value || "",
        };

        console.log("Form data:", formData);

        // Get CSRF token from meta tag
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

        if (!csrfToken) {
            alert("Error: CSRF token not found");
            console.error("CSRF token missing");
            return;
        }

        // Show loading state
        const saveBtn = row.querySelector(".save-btn");
        const originalText = saveBtn.textContent;
        saveBtn.textContent = "Saving...";
        saveBtn.disabled = true;

        fetch(`/admin/reports/ched-monitoring/update-grade/${scholarId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
            body: JSON.stringify(formData),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                console.log("Server response:", data);

                if (data.success) {
                    // Update the view-mode spans with new values
                    updateViewModeValues(row, formData);

                    // Exit edit mode
                    exitEditMode(row);

                    // Show success message
                    if (window.showFeedback) {
                        window.showFeedback(
                            "Grade report updated successfully!"
                        );
                    } else {
                        showNotification(
                            "Grade report updated successfully!",
                            "success"
                        );
                    }
                } else {
                    throw new Error(data.message || "Unknown error");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Failed to update: " + error.message);
            })
            .finally(() => {
                saveBtn.textContent = originalText;
                saveBtn.disabled = false;
            });
    }

    function updateViewModeValues(row, formData) {
        // Update all view-mode spans with new values
        const viewModeMap = {
            degree_program: ".col-gr_degree_program .view-mode",
            enrolled_subjects: ".col-gr_enrolled_subjects .view-mode",
            subjects_passed: ".col-gr_subjects_passed .view-mode",
            incomplete_grades: ".col-gr_incomplete_grades .view-mode",
            subjects_failed: ".col-gr_subjects_failed .view-mode",
            no_grades: ".col-gr_no_grades .view-mode",
            not_credited_subjects: ".col-gr_not_credited .view-mode",
            status: ".col-gr_status .view-mode",
            gpa: ".col-gr_gpa .view-mode",
            remarks: ".col-gr_remarks .view-mode",
        };

        Object.keys(viewModeMap).forEach((key) => {
            const span = row.querySelector(viewModeMap[key]);
            if (span && formData[key] !== undefined) {
                if (key === "gpa" && formData[key]) {
                    span.textContent = parseFloat(formData[key]).toFixed(2);
                } else {
                    span.textContent = formData[key];
                }
            }
        });
    }

    function showNotification(message, type = "success") {
        const notification = document.createElement("div");
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === "success" ? "bg-green-500" : "bg-red-500"
        } text-white`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});
