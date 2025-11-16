// Enrollment Report Edit Functionality
document.addEventListener("DOMContentLoaded", function () {
    const enrollmentDiv = document.querySelector("#table-enrollment");
    const modal = document.getElementById("addScholarModal");
    const scholarSearch = document.getElementById("scholarSearch");
    let currentTable = null;

    if (!enrollmentDiv) {
        console.error("Enrollment report div not found");
        return;
    }

    console.log("Enrollment Report Edit Script Loaded");

    // Add Scholar Button Click
    document.querySelectorAll(".add-scholar-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            currentTable = this.dataset.table;
            modal.classList.remove("hidden");
        });
    });

    // Close Modal
    document.querySelectorAll(".close-modal").forEach((btn) => {
        btn.addEventListener("click", function () {
            modal.classList.add("hidden");
            currentTable = null;
        });
    });

    // Click outside modal to close
    modal.addEventListener("click", function (e) {
        if (e.target === modal) {
            modal.classList.add("hidden");
            currentTable = null;
        }
    });

    // Search Scholars
    if (scholarSearch) {
        scholarSearch.addEventListener("input", function () {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll(".scholar-item").forEach((item) => {
                const name = item.dataset.name.toLowerCase();
                const appNo = item.dataset.application.toLowerCase();
                if (name.includes(searchTerm) || appNo.includes(searchTerm)) {
                    item.style.display = "";
                } else {
                    item.style.display = "none";
                }
            });
        });
    }

    // Select Scholar
    document.querySelectorAll(".select-scholar-btn").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.stopPropagation();
            const scholarItem = this.closest(".scholar-item");
            const scholarId = scholarItem.dataset.scholarId;

            if (!currentTable) {
                alert("Error: No table selected");
                return;
            }

            addScholarToTable(scholarId, currentTable);
        });
    });

    function addScholarToTable(scholarId, tableType) {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

        if (!csrfToken) {
            alert("Error: CSRF token not found");
            return;
        }

        fetch(`/admin/reports/ched-monitoring/add-to-enrollment/${scholarId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
            body: JSON.stringify({ table_type: tableType }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    modal.classList.add("hidden");
                    showNotification(
                        "Scholar added successfully! Refreshing page...",
                        "success"
                    );
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.message || "Unknown error");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Failed to add scholar: " + error.message);
            });
    }

    // Use event delegation - attach to enrollment div to handle all 4 tables
    enrollmentDiv.addEventListener("click", function (e) {
        // Handle Edit button click
        if (e.target.classList.contains("edit-btn")) {
            const row = e.target.closest("tr");
            const table = e.target.closest("table");
            enterEditMode(row, table);
        }

        // Handle Cancel button click
        if (e.target.classList.contains("cancel-btn")) {
            const row = e.target.closest("tr");
            exitEditMode(row);
        }

        // Handle Save button click
        if (e.target.classList.contains("save-btn")) {
            const row = e.target.closest("tr");
            const table = e.target.closest("table");
            saveEnrollmentReport(row, table);
        }
    });

    function enterEditMode(row, table) {
        console.log("Entering edit mode for row:", row);

        const tableType = table.dataset.table; // Get table type (a, b, c, or d)
        console.log("Table type:", tableType);

        // Store original values before editing
        const inputs = row.querySelectorAll("input, select, textarea");
        const originalValues = {};

        inputs.forEach((input) => {
            if (input.name) {
                originalValues[input.name] = input.value;
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

    function saveEnrollmentReport(row, table) {
        const scholarId = row.dataset.scholarId;
        const tableType = table.dataset.table; // Get table type (a, b, c, or d)

        if (!scholarId) {
            alert("Error: Scholar ID not found");
            console.error("Scholar ID missing from row:", row);
            return;
        }

        console.log(
            "Saving enrollment report for scholar ID:",
            scholarId,
            "Table:",
            tableType
        );

        // Collect form data based on table type
        let formData = {
            table_type: tableType,
            degree_program:
                row.querySelector('[name="degree_program"]')?.value || "",
        };

        // Add table-specific fields
        if (tableType === "a") {
            formData.enrollment_status =
                row.querySelector('[name="enrollment_status"]')?.value || "";
            formData.units_enrolled =
                row.querySelector('[name="units_enrolled"]')?.value || "";
            formData.retaken_subjects =
                row.querySelector('[name="retaken_subjects"]')?.value || "";
            formData.remarks =
                row.querySelector('[name="remarks"]')?.value || "";
        } else if (tableType === "b") {
            formData.issue_status =
                row.querySelector('[name="issue_status"]')?.value || "";
            formData.others_status =
                row.querySelector('[name="others_status"]')?.value || "";
            formData.status_description =
                row.querySelector('[name="status_description"]')?.value || "";
        } else if (tableType === "c") {
            formData.non_enrollment_status =
                row.querySelector('[name="non_enrollment_status"]')?.value ||
                "";
            formData.others_status =
                row.querySelector('[name="others_status"]')?.value || "";
            formData.status_description =
                row.querySelector('[name="status_description"]')?.value || "";
        } else if (tableType === "d") {
            formData.termination_status =
                row.querySelector('[name="termination_status"]')?.value || "";
            formData.others_status =
                row.querySelector('[name="others_status"]')?.value || "";
            formData.status_description =
                row.querySelector('[name="status_description"]')?.value || "";
        }

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

        fetch(`/admin/reports/ched-monitoring/update-enrollment/${scholarId}`, {
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
                    updateViewModeValues(row, formData, tableType);

                    // Exit edit mode
                    exitEditMode(row);

                    // Show success message
                    if (window.showFeedback) {
                        window.showFeedback(
                            "Enrollment report updated successfully!"
                        );
                    } else {
                        showNotification(
                            "Enrollment report updated successfully!",
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

    function updateViewModeValues(row, formData, tableType) {
        // Update degree program (common to all tables)
        const degreeSpan = row.querySelector(
            `.col-enr_${tableType}_degree_program .view-mode`
        );
        if (degreeSpan) degreeSpan.textContent = formData.degree_program;

        // Update table-specific fields
        if (tableType === "a") {
            updateSpan(
                row,
                `.col-enr_a_status .view-mode`,
                formData.enrollment_status
            );
            updateSpan(
                row,
                `.col-enr_a_units_enrolled .view-mode`,
                formData.units_enrolled
            );
            updateSpan(
                row,
                `.col-enr_a_retaken_subjects .view-mode`,
                formData.retaken_subjects
            );
            updateSpan(row, `.col-enr_a_remarks .view-mode`, formData.remarks);
        } else if (tableType === "b") {
            updateSpan(
                row,
                `.col-enr_b_status .view-mode`,
                formData.issue_status
            );
            updateSpan(
                row,
                `.col-enr_b_others_status .view-mode`,
                formData.others_status
            );
            updateSpan(
                row,
                `.col-enr_b_description .view-mode`,
                formData.status_description
            );
        } else if (tableType === "c") {
            updateSpan(
                row,
                `.col-enr_c_status .view-mode`,
                formData.non_enrollment_status
            );
            updateSpan(
                row,
                `.col-enr_c_others_status .view-mode`,
                formData.others_status
            );
            updateSpan(
                row,
                `.col-enr_c_description .view-mode`,
                formData.status_description
            );
        } else if (tableType === "d") {
            updateSpan(
                row,
                `.col-enr_d_status .view-mode`,
                formData.termination_status
            );
            updateSpan(
                row,
                `.col-enr_d_others_status .view-mode`,
                formData.others_status
            );
            updateSpan(
                row,
                `.col-enr_d_description .view-mode`,
                formData.status_description
            );
        }
    }

    function updateSpan(row, selector, value) {
        const span = row.querySelector(selector);
        if (span && value !== undefined) {
            span.textContent = value;
        }
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
