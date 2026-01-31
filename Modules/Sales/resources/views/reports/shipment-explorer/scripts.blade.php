<script>
    $(document).ready(function() {
        var allColumns = [
            'invoice-id', 'datetime', 'customer', 'courier', 'status', 'shipment-type',
            'amount', 'additional', 'conditional', 'remarks', 'carton', 'receipt-date',
            'receipt-no', 'service-charge', 'service-type', 'delivery-charge', 'delivery-type',
            'other-charge', 'other-type', 'attachment', 'update-by', 'collection-by',
            'approved-by', 'user', 'complete-date', 'challan'
        ];

        // Initialize modal
        $('#columnFilterModal').modal({
            show: false,
            backdrop: true,
            keyboard: true
        });

        // Open modal on button click
        $('button[data-target="#columnFilterModal"]').on('click', function(e) {
            e.preventDefault();
            $('#columnFilterModal').modal('show');
        });

        // Show all columns function
        function showAllColumns() {
            $('#shipmentExplorerTable thead th').show();
            $('#shipmentExplorerTable tbody tr').each(function() {
                $(this).find('td').show();
            });
            $('#shipmentExplorerTable tfoot th, #shipmentExplorerTable tfoot td').show();
        }

        // When modal opens, check visible columns
        $('#columnFilterModal').on('show.bs.modal', function() {
            allColumns.forEach(function(colKey) {
                var header = $('#shipmentExplorerTable thead th.col-' + colKey);
                var checkbox = $('#col_' + colKey);
                if (header.length && header.is(':visible')) {
                    checkbox.prop('checked', true);
                } else {
                    checkbox.prop('checked', false);
                }
            });
        });

        // Apply column filter
        $('#applyColumnFilter').on('click', function() {
            var selectedColumns = [];
            $('#columnFilterForm input[type="checkbox"]:checked').each(function() {
                selectedColumns.push($(this).val());
            });

            // Show all columns first
            showAllColumns();

            // Hide unselected columns
            allColumns.forEach(function(colKey) {
                if (!selectedColumns.includes(colKey)) {
                    var header = $('#shipmentExplorerTable thead th.col-' + colKey);
                    if (header.length) {
                        var idx = header.index();
                        header.hide();
                        $('#shipmentExplorerTable tbody tr').each(function() {
                            $(this).find('td').eq(idx).hide();
                        });
                        $('#shipmentExplorerTable tfoot tr').each(function() {
                            $(this).find('th, td').eq(idx).hide();
                        });
                    }
                }
            });

            $('#columnFilterModal').modal('hide');
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Column filter applied successfully!',
                timer: 1500,
                showConfirmButton: false
            });
        });

        // Initialize tooltips if available
        if ($.fn.tooltip) {
            $('[data-toggle="tooltip"]').tooltip();
        }

       
    });
</script>
<script>
$(document).ready(function() {
    // Store originally checked columns
    let originalColumns = [];
    
    // When modal opens, save the current state
    $('#columnFilterModal').on('show.bs.modal', function() {
        originalColumns = [];
        $('#columnFilterForm input[type="checkbox"]:checked').each(function() {
            originalColumns.push($(this).val());
        });
    });
    
    // Apply column filter
    $('#applyColumnFilter').on('click', function() {
        const selectedColumns = [];
        $('#columnFilterForm input[type="checkbox"]:checked').each(function() {
            selectedColumns.push($(this).val());
        });
        
        if (selectedColumns.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Columns Selected',
                text: 'Please select at least one column to display.',
            });
            return;
        }
        
        // Hide all column-specific elements first
        const allColumns = [
            'invoice-id', 'datetime', 'customer', 'courier', 'status', 'shipment-type',
            'amount', 'additional', 'conditional', 'remarks', 'carton', 'receipt-date',
            'receipt-no', 'service-charge', 'service-type', 'delivery-charge', 'delivery-type',
            'other-charge', 'other-type', 'attachment', 'update-by', 'collection-by',
            'approved-by', 'user', 'complete-date', 'challan'
        ];
        
        allColumns.forEach(function(col) {
            $('.col-' + col).hide();
        });
        
        // Show only selected columns
        selectedColumns.forEach(function(col) {
            $('.col-' + col).show();
        });
        
        // Close modal
        $('#columnFilterModal').modal('hide');
        
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Columns Updated',
            text: `${selectedColumns.length} column(s) are now visible.`,
            timer: 1500,
            showConfirmButton: false
        });
    });
    
    // Update PDF/Excel export links with selected columns
    function updateExportLinks() {
        const selectedColumns = [];
        $('#columnFilterForm input[type="checkbox"]:checked').each(function() {
            selectedColumns.push($(this).val());
        });
        
        const columnsParam = selectedColumns.join(',');
        
        // Update PDF link
        const pdfLink = $('a[href*="export_type=pdf"]');
        if (pdfLink.length) {
            let href = pdfLink.attr('href');
            // Remove existing columns parameter if any
            href = href.replace(/&columns=[^&]*/, '');
            // Add new columns parameter
            if (selectedColumns.length > 0) {
                href += '&columns=' + encodeURIComponent(columnsParam);
            }
            pdfLink.attr('href', href);
        }
        
        // Update Excel link
        const excelLink = $('a[href*="export_type=excel"]');
        if (excelLink.length) {
            let href = excelLink.attr('href');
            // Remove existing columns parameter if any
            href = href.replace(/&columns=[^&]*/, '');
            // Add new columns parameter
            if (selectedColumns.length > 0) {
                href += '&columns=' + encodeURIComponent(columnsParam);
            }
            excelLink.attr('href', href);
        }
    }
    
    // Update export links when column selection changes
    $('#columnFilterForm input[type="checkbox"]').on('change', function() {
        updateExportLinks();
    });
    
    // Update export links on modal close
    $('#columnFilterModal').on('hidden.bs.modal', function() {
        updateExportLinks();
    });
    
    // Select All / Deselect All functionality
    $('#columnFilterModal .modal-header').append(`
        <div class="ms-auto me-3 btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary me-2" id="selectAllColumns">
                <i class="fa fa-check-square"></i> Select All
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllColumns">
                <i class="fa fa-square"></i> Deselect All
            </button>
        </div>
    `);
    
    // Select all columns
    $(document).on('click', '#selectAllColumns', function() {
        $('#columnFilterForm input[type="checkbox"]').prop('checked', true);
        updateExportLinks();
    });
    
    // Deselect all columns
    $(document).on('click', '#deselectAllColumns', function() {
        $('#columnFilterForm input[type="checkbox"]').prop('checked', false);
        updateExportLinks();
    });
    
    // Initialize export links on page load
    updateExportLinks();
    
    // Handle reset button
    $('.btn-warning[href*="shipment-explorer"]').on('click', function(e) {
        // Reset column filters to default
        $('#columnFilterForm input[type="checkbox"]').prop('checked', true);
        updateExportLinks();
    });
    
    // Save column preferences to localStorage
    function saveColumnPreferences() {
        const selectedColumns = [];
        $('#columnFilterForm input[type="checkbox"]:checked').each(function() {
            selectedColumns.push($(this).val());
        });
        localStorage.setItem('shipmentExplorerColumns', JSON.stringify(selectedColumns));
    }
    
    // Load column preferences from localStorage
    function loadColumnPreferences() {
        const saved = localStorage.getItem('shipmentExplorerColumns');
        if (saved) {
            try {
                const selectedColumns = JSON.parse(saved);
                
                // Uncheck all first
                $('#columnFilterForm input[type="checkbox"]').prop('checked', false);
                
                // Check saved columns
                selectedColumns.forEach(function(col) {
                    $('#columnFilterForm input[value="' + col + '"]').prop('checked', true);
                });
                
                // Apply the saved preferences
                $('#applyColumnFilter').trigger('click');
            } catch (e) {
                console.error('Error loading column preferences:', e);
            }
        }
    }
    
    // Save preferences when columns are applied
    $('#applyColumnFilter').on('click', function() {
        saveColumnPreferences();
    });
    
    // Load preferences on page load
    // Uncomment the line below if you want to auto-load saved preferences
    // loadColumnPreferences();
});
</script>

<style>
/* Additional styles for column filter modal */
#columnFilterModal .custom-control-label {
    cursor: pointer;
    user-select: none;
}

#columnFilterModal .custom-control {
    padding: 8px 0;
}


#columnFilterModal .modal-header {
    display: flex;
    align-items: center;
}

#columnFilterModal .modal-header .btn {
    font-size: 12px;
    padding: 4px 12px;
}

/* Smooth transitions for column visibility */
.table th, .table td {
    transition: all 0.3s ease;
}
</style>