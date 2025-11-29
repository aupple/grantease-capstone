// Continuing Eligibility Report Edit Functionality
document.addEventListener("DOMContentLoaded", function () {
    const continuingDiv = document.querySelector("#table-continuing");
    const modal = document.getElementById("addContinuingModal");
    const scholarSearch = document.getElementById("continuingScholarSearch");
    let currentTable = null;

    if (!continuingDiv) {
        console.error("Continuing eligibility report div not found");
        return;
    }

    console.log("Continuing Eligibility Report Edit Script Loaded");

    // Add Scholar Button Click
    document.querySelectorAll(".add-continuing-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            currentTable = this.dataset.table;
            modal.classList.remove("hidden");
        });
    });

    // Close Modal
    document.querySelectorAll(".close-continuing-modal").forEach((btn) => {
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
            document
                .querySelectorAll(".continuing-scholar-item")
                .forEach((item) => {
                    const name = item.dataset.name.toLowerCase();
                    const appNo = item.dataset.application.toLowerCase();
                    if (
                        name.includes(searchTerm) ||
                        appNo.includes(searchTerm)
                    ) {
                        item.style.display = "";
                    } else {
                        item.style.display = "none";
                    }
                });
        });
    }

    // Select Scholar
    document
        .querySelectorAll(".select-continuing-scholar-btn")
        .forEach((btn) => {
            btn.addEventListener("click", function (e) {
                e.stopPropagation();
                const scholarItem = this.closest(".continuing-scholar-item");
                const scholarId = scholarItem.dataset.scholarId;

                if (!currentTable) {
                    alert("Error: No table selected");
                    return;
                }

                addScholarToContinuing(scholarId, currentTable);
            });
        });

    function addScholarToContinuing(scholarId, tableType) {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

        if (!csrfToken) {
            alert("Error: CSRF token not found");
            return;
        }

        fetch(`/admin/reports/ched-monitoring/add-to-continuing/${scholarId}`, {
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

    // Use event delegation for edit/save/cancel on continuing tables
    continuingDiv.addEventListener("click", function (e) {
        const editBtn = e.target.closest(".edit-btn");
        const cancelBtn = e.target.closest(".cancel-btn");
        const saveBtn = e.target.closest(".save-btn");

        if (editBtn) {
            const row = editBtn.closest("tr");
            const table = editBtn.closest("table");
            enterEditMode(row, table);
        }

        if (cancelBtn) {
            const row = cancelBtn.closest("tr");
            exitEditMode(row);
        }

        if (saveBtn) {
            const row = saveBtn.closest("tr");
            const table = saveBtn.closest("table");
            saveContinuingReport(row, table);
        }
    });

    function enterEditMode(row, table) {
        console.log("Entering edit mode for row:", row);

        const tableType = table.dataset.table;
        console.log("Table type:", tableType);

        const originalValues = {};

        // Store current visible values (from view-mode spans)
        row.querySelectorAll("input, select, textarea").forEach((input) => {
            if (input.name) {
                // Get the current value from view-mode if available
                const viewModeEl = input
                    .closest("td")
                    ?.querySelector(".view-mode");
                if (viewModeEl) {
                    // For selects (Yes/No), extract the text content
                    const displayValue = viewModeEl.textContent.trim();
                    if (input.tagName === "SELECT") {
                        // Map "Yes" to "1" and "No" to "0"
                        if (displayValue === "Yes")
                            originalValues[input.name] = "1";
                        else if (displayValue === "No")
                            originalValues[input.name] = "0";
                        else originalValues[input.name] = input.value;
                    } else {
                        originalValues[input.name] =
                            displayValue || input.value;
                    }
                } else {
                    originalValues[input.name] = input.value;
                }
            }
        });

        row.dataset.originalValues = JSON.stringify(originalValues);

        row.querySelectorAll(".view-mode").forEach((el) =>
            el.classList.add("hidden")
        );
        row.querySelectorAll(".edit-mode").forEach((el) =>
            el.classList.remove("hidden")
        );
    }

    function exitEditMode(row) {
        console.log("Exiting edit mode for row:", row);

        if (row.dataset.originalValues) {
            const originalValues = JSON.parse(row.dataset.originalValues);

            Object.keys(originalValues).forEach((key) => {
                const input = row.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = originalValues[key];
                }
            });
        }

        // Clear the stored original values
        delete row.dataset.originalValues;

        row.querySelectorAll(".edit-mode").forEach((el) =>
            el.classList.add("hidden")
        );
        row.querySelectorAll(".view-mode").forEach((el) =>
            el.classList.remove("hidden")
        );
    }

    function saveContinuingReport(row, table) {
        const scholarId = row.dataset.scholarId;
        const tableType = table.dataset.table;

        if (!scholarId) {
            alert("Error: Scholar ID not found");
            console.error("Scholar ID missing from row:", row);
            return;
        }

        console.log(
            "Saving continuing report for scholar ID:",
            scholarId,
            "Table:",
            tableType
        );

        let formData = {
            table_type: tableType,
            scholarship_type:
                row.querySelector('[name="scholarship_type"]')?.value || "",
            degree_program:
                row.querySelector('[name="degree_program"]')?.value || "",
        };

        if (tableType === "a") {
            formData.year_of_approval =
                row.querySelector('[name="year_of_approval"]')?.value || "";
            formData.last_term_enrollment =
                row.querySelector('[name="last_term_enrollment"]')?.value || "";
            formData.good_academic_standing =
                row.querySelector('[name="good_academic_standing"]')?.value ||
                "";
            formData.standing_explanation =
                row.querySelector('[name="standing_explanation"]')?.value || "";
            formData.finish_on_time =
                row.querySelector('[name="finish_on_time"]')?.value || "";
            formData.finish_explanation =
                row.querySelector('[name="finish_explanation"]')?.value || "";
            formData.recommendation =
                row.querySelector('[name="recommendation"]')?.value || "";
            formData.rationale =
                row.querySelector('[name="rationale"]')?.value || "";
        } else if (tableType === "b") {
            formData.academic_year_graduation =
                row.querySelector('[name="academic_year_graduation"]')?.value ||
                "";
            formData.term_of_graduation =
                row.querySelector('[name="term_of_graduation"]')?.value || "";
            formData.remarks =
                row.querySelector('[name="remarks"]')?.value || "";
        }

        console.log("Form data:", formData);

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

        if (!csrfToken) {
            alert("Error: CSRF token not found");
            return;
        }

        const saveBtn = row.querySelector(".save-btn");
        const originalText = saveBtn.textContent;
        saveBtn.textContent = "Saving...";
        saveBtn.disabled = true;

        fetch(`/admin/reports/ched-monitoring/update-continuing/${scholarId}`, {
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
                    updateViewModeValues(row, formData, tableType);
                    exitEditMode(row);

                    if (window.showFeedback) {
                        window.showFeedback(
                            "Continuing report updated successfully!"
                        );
                    } else {
                        showNotification(
                            "Continuing report updated successfully!",
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
        // Common fields
        updateSpan(
            row,
            `.col-cont_${tableType}_scholarship_type .view-mode`,
            formData.scholarship_type
        );
        updateSpan(
            row,
            `.col-cont_${tableType}_degree_program .view-mode`,
            formData.degree_program
        );

        if (tableType === "a") {
            updateSpan(
                row,
                `.col-cont_a_year_approval .view-mode`,
                formData.year_of_approval
            );
            updateSpan(
                row,
                `.col-cont_a_last_term .view-mode`,
                formData.last_term_enrollment
            );
            updateSpan(
                row,
                `.col-cont_a_standing_explanation .view-mode`,
                formData.standing_explanation
            );
            updateSpan(
                row,
                `.col-cont_a_finish_explanation .view-mode`,
                formData.finish_explanation
            );
            updateSpan(
                row,
                `.col-cont_a_recommendation .view-mode`,
                formData.recommendation
            );
            updateSpan(
                row,
                `.col-cont_a_rationale .view-mode`,
                formData.rationale
            );

            // Update Yes/No as plain text (no badges)
            updateSpan(
                row,
                `.col-cont_a_good_standing .view-mode`,
                formData.good_academic_standing == 1
                    ? "Yes"
                    : formData.good_academic_standing == 0
                    ? "No"
                    : ""
            );

            updateSpan(
                row,
                `.col-cont_a_finish_on_time .view-mode`,
                formData.finish_on_time == 1
                    ? "Yes"
                    : formData.finish_on_time == 0
                    ? "No"
                    : ""
            );
        } else if (tableType === "b") {
            updateSpan(
                row,
                `.col-cont_b_academic_year .view-mode`,
                formData.academic_year_graduation
            );
            updateSpan(
                row,
                `.col-cont_b_term_graduation .view-mode`,
                formData.term_of_graduation
            );
            updateSpan(row, `.col-cont_b_remarks .view-mode`, formData.remarks);
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
