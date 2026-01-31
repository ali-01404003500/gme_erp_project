<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>

    function removeColumnsByIndex(tableContainer, indexList) {
        const table = tableContainer.tagName === 'TABLE' ? tableContainer : tableContainer.querySelector('table');
        if (!table) {
            console.error('No table found in the provided container.');
            return;
        }
        // Get the number of columns in the table
        const numCols = table.rows[0].cells.length;

        // Iterate over each row in the table
        for (let i = 0; i < table.rows.length; i++) {
            // Iterate over the columns to be removed in reverse order
            for (let j = indexList.length - 1; j >= 0; j--) {
                // Remove the column at the current index
                table.rows[i].deleteCell(indexList[j]);
            }
        }
    }
    
    function initCustomPdf(button_id, table_id, optionsArgs = {}) {
        const options = { excludeColumns: [], title: '', subtitle: '', rowFilter: null ,...optionsArgs };
        // append modal in body 
        $("body").append(`
                <div class="modal fade" id="confirm_pdf" tabindex="-1" aria-labelledby="fullscreenModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 90%; width: 100%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="fullscreenModalLabel">Custom PDF</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Modal body content goes here -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary download-pdf">Download PDF</button>

                                ${options.exportExcel ? '<button type="button" class="btn btn-primary download-excel">Download Excel</button>' : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `);
                            // Helper function to remove columns by index
            function removeColumnsByIndex(table, indexes) {
                if (indexes.length === 0) return;
                
                // Remove from header
                const headerRows = table.querySelectorAll('thead tr');
                headerRows.forEach(row => {
                    const cells = Array.from(row.children);
                    indexes.sort((a, b) => b - a).forEach(idx => {
                        if (cells[idx]) cells[idx].remove();
                    });
                });
                
                // Remove from body
                const bodyRows = table.querySelectorAll('tbody tr');
                bodyRows.forEach(row => {
                    const cells = Array.from(row.children);
                    indexes.sort((a, b) => b - a).forEach(idx => {
                        if (cells[idx]) cells[idx].remove();
                    });
                });
            }

            // Helper function to recalculate serial numbers
            function recalculateSerialColumn(table) {
                // Find the SL column index by checking header text
                const headers = table.querySelectorAll('thead th');
                let slColumnIndex = -1;
                
                headers.forEach((th, idx) => {
                    const headerText = th.innerText.trim().toLowerCase();
                    if (headerText === 'sl' || headerText === 's.l' || headerText === 'serial' || headerText === '#' || headerText === 's/n' || headerText === 'no') {
                        slColumnIndex = idx;
                    }
                });
                
                // If SL column found, renumber the rows
                if (slColumnIndex !== -1) {
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach((row, index) => {
                        const cells = row.querySelectorAll('td, th');
                        if (cells[slColumnIndex]) {
                            cells[slColumnIndex].innerText = index + 1;
                        }
                    });
                }
            }

            // Main export function
            document.getElementById(button_id).addEventListener('click', function () {
                const table = document.querySelector('#' + table_id).cloneNode(true);
                const columns = table.querySelectorAll('th');
                // Use the #confirm_pdf modal to let user select columns to export
                const modal = document.getElementById('confirm_pdf');
                const modalBody = modal.querySelector('.modal-body');
                const modalFooter = modal.querySelector('.modal-footer');

                // Get column names
                const columnNames = Array.from(columns).map((col) => {
                    let colText = col.innerText.trim();
                    if (!colText) {
                        colText = col.id;
                        if (!colText) {
                            colText = col.innerHTML.trim().match(/<(\w+)/)[1];
                        }
                    }
                    return colText;
                });

                // Build the form for column selection
                let formHtml = '<form id="columnSelectForm" style="display: inline-block;">';
                columnNames.forEach((name, idx) => {
                    formHtml += `
                    <div class="form-check d-inline-block me-2" style="margin-right: 0.5rem !important;">
                    <input class="form-check-input" type="checkbox" value="${idx}" id="colCheck${idx}" ${options.excludeColumns.includes(idx) ? '' : 'checked'} style="margin-right: 0.25rem !important;">
                    <label class="form-check-label" for="colCheck${idx}" style="margin-right: 1rem !important;">${name}</label>
                    </div>
                    `;
                });
                formHtml += '</form>';

                // Add a preview area for the table
                formHtml += '<div id="tablePreview" class="table-responsive" style="margin-top: 20px; overflow-x:auto;"></div>';

                modalBody.innerHTML = formHtml;

                // Function to update the table preview based on selected columns
                function updateTablePreview() {
                    // Clone the table again to avoid mutation
                    const originalTable = table;
                    const previewTable = originalTable.cloneNode(true);

                    // Keep only the first 10 rows in tbody
                    const tbody = previewTable.querySelector('tbody');
                    if (tbody) {
                        const rows = Array.from(tbody.querySelectorAll('tr'));
                        rows.forEach((row, idx) => {
                            if (idx >= 10) row.remove();
                        });
                    }

                    const checked = Array.from(modalBody.querySelectorAll('#columnSelectForm input[type=checkbox]')).map(cb => cb.checked);
                    const removeIndexes = [];
                    checked.forEach((isChecked, idx) => {
                        if (!isChecked) removeIndexes.push(idx);
                    });
                    removeColumnsByIndex(previewTable, removeIndexes);
                    
                    // Recalculate serial numbers for preview
                    recalculateSerialColumn(previewTable);
                    
                    // Set preview
                    modalBody.querySelector('#tablePreview').innerHTML = previewTable.outerHTML;
                }

                // Initial preview
                updateTablePreview();

                // Add listener for checkbox change
                const form = modalBody.querySelector('#columnSelectForm');
                form.querySelectorAll('input[type=checkbox]').forEach(cb => {
                    cb.addEventListener('change', function () {
                        updateTablePreview();
                    });
                });

                // Remove any previous event listeners on modal-footer buttons
                const okBtn = modalFooter.querySelector('.btn.btn-primary.download-pdf');
                const closeBtn = modalFooter.querySelector('.btn.btn-secondary');
                const newOkBtn = okBtn.cloneNode(true);
                okBtn.parentNode.replaceChild(newOkBtn, okBtn);

                // Show the modal
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                // OK button handler (PDF Export)
                newOkBtn.onclick = function () {
                    const form = modalBody.querySelector('#columnSelectForm');
                    const checked = Array.from(form.querySelectorAll('input[type=checkbox]')).map(cb => cb.checked);
                    const removeIndexes = [];
                    checked.forEach((isChecked, idx) => {
                        if (!isChecked) removeIndexes.push(idx);
                    });
                    const tableToExport = table.cloneNode(true);
                    // Remove columns from cloned table
                    removeColumnsByIndex(tableToExport, removeIndexes);

                    // Hide modal
                    bsModal.hide();

                    // Continue with export
                    const url = "{{ route('pdf_report') }}";
                    const { title, subtitle, rowFilter } = options;

                    if (typeof rowFilter === 'function') {
                        const rows = table.querySelectorAll('tbody tr');
                        const exportRows = tableToExport.querySelectorAll('tbody tr');
                        const tableRows = Array.from(rows);
                        const exportTableRows = Array.from(exportRows);
                        tableRows.forEach((row, idx) => {
                            if (!rowFilter(row, idx)) {
                                exportTableRows[idx].remove();
                            }
                        });
                    }

                    // Recalculate serial numbers after filtering
                    recalculateSerialColumn(tableToExport);

                    const tableContent = btoa(unescape(encodeURIComponent(tableToExport.outerHTML)));

                    const form_submit = document.createElement('form');
                    form_submit.action = url;
                    form_submit.method = "POST";
                    form_submit.target = "_blank";
                    const titleInput = document.createElement('input');
                    titleInput.type = "hidden";
                    titleInput.name = "title";
                    titleInput.value = title;
                    form_submit.appendChild(titleInput);
                    const subtitleInput = document.createElement('input');
                    subtitleInput.type = "hidden";
                    subtitleInput.name = "subtitles";
                    subtitleInput.value = subtitle;
                    form_submit.appendChild(subtitleInput);
                    const tableInput = document.createElement('input');
                    tableInput.type = "hidden";
                    tableInput.name = "table";
                    tableInput.value = tableContent;
                    form_submit.appendChild(tableInput);
                    document.body.appendChild(form_submit);
                    form_submit.submit();
                };

                // Excel button handler
                if (options.exportExcel) {
                    const excelBtn = modalFooter.querySelector('.btn.btn-primary.download-excel');
                    const newExcelBtn = excelBtn.cloneNode(true);
                    excelBtn.parentNode.replaceChild(newExcelBtn, excelBtn);

                    newExcelBtn.onclick = function () {
                        const form = modalBody.querySelector('#columnSelectForm');
                        const checked = Array.from(form.querySelectorAll('input[type=checkbox]')).map(cb => cb.checked);
                        const removeIndexes = [];
                        checked.forEach((isChecked, idx) => {
                            if (!isChecked) removeIndexes.push(idx);
                        });
                        const tableToExport = table.cloneNode(true);
                        // Remove columns from cloned table
                        removeColumnsByIndex(tableToExport, removeIndexes);

                        // Hide modal
                        bsModal.hide();

                        // Continue with export
                        const { title, subtitle, rowFilter } = options;

                        if (typeof rowFilter === 'function') {
                            const rows = table.querySelectorAll('tbody tr');
                            const exportRows = tableToExport.querySelectorAll('tbody tr');
                            const tableRows = Array.from(rows);
                            const exportTableRows = Array.from(exportRows);
                            tableRows.forEach((row, idx) => {
                                if (!rowFilter(row, idx)) {
                                    exportTableRows[idx].remove();
                                }
                            });
                        }

                        // Recalculate serial numbers after filtering
                        recalculateSerialColumn(tableToExport);

                        // Prepare worksheet data with title and subtitle rows
                        const colCount = tableToExport.querySelectorAll('tr')[0].children.length;
                        const ws_data = [
                            [{ v: title, t: 's', s: { font: { bold: true, sz: 14 } }, }, ...Array(colCount - 1).fill('')],
                            [{ v: subtitle, t: 's', s: { font: { italic: true, sz: 12 } } }, ...Array(colCount - 1).fill('')]
                        ];

                        // Extract table head (th) row
                        const thead = tableToExport.querySelector('thead');
                        if (thead) {
                            const headRow = Array.from(thead.querySelectorAll('tr'))[0];
                            if (headRow) {
                                const row = Array.from(headRow.children).map(th => th.innerText);
                                ws_data.push(row);
                            }
                        } else {
                            // fallback: use first tr as header if thead is missing
                            const firstTr = tableToExport.querySelector('tr');
                            if (firstTr) {
                                const row = Array.from(firstTr.children).map(td => td.innerText);
                                ws_data.push(row);
                            }
                        }

                        // Extract table body rows
                        const tbody = tableToExport.querySelector('tbody');
                        if (tbody) {
                            Array.from(tbody.querySelectorAll('tr')).forEach(tr => {
                                const row = Array.from(tr.children).map(td => td.innerText);
                                ws_data.push(row);
                            });
                        } else {
                            // fallback: add all rows except the first (header) row
                            const tableRows = Array.from(tableToExport.querySelectorAll('tr'));
                            tableRows.slice(1).forEach(tr => {
                                const row = Array.from(tr.children).map(td => td.innerText);
                                ws_data.push(row);
                            });
                        }

                        // Create worksheet and merge title/subtitle cells
                        const ws = XLSX.utils.aoa_to_sheet(ws_data);
                        ws['!merges'] = [
                            { s: { r: 0, c: 0 }, e: { r: 0, c: colCount - 1 } },
                            { s: { r: 1, c: 0 }, e: { r: 1, c: colCount - 1 } }
                        ];

                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, title || 'Sheet1');
                        const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
                        const link = document.createElement("a");
                        link.href = URL.createObjectURL(new Blob([wbout], { type: 'application/octet-stream' }));
                        link.setAttribute('download', `${title || 'Export'}.xlsx`);
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                    };
                }
            });

    }
</script>

