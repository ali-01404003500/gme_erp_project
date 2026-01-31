<div class="table-filter dropdown">
    <button class="btn btn-outline-primary btn-xs d-flex align-items-center filter-toggle">
        <i class="fa fa-filter"></i>
        <span class="ms-1">Filter</span>
        <span
            class="badge bg-secondary ms-2">{{ count(array_filter(request()->all(), fn($value) => $value !== null)) }}</span>
    </button>

    <div class="position-absolute border shadow bg-body rounded table-filter-popover d-none"
        style="width: 380px; z-index: 1024;">
        <form class="px-4 py-3 position-relative">
            {{ $slot }}
            <div class="d-flex justify-content-between">
                <a type="reset" href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.querySelector('.filter-toggle');
        const filterPopover = document.querySelector('.table-filter-popover');
        const filterForm = filterPopover.querySelector('form');

        // Toggle popover on button click
        filterButton.addEventListener('click', function(e) {
            e.stopPropagation();
            filterPopover.classList.toggle('d-none');
        });

        // Prevent closing when clicking inside the form
        filterForm.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Close popover when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('flatpickr-calendar')) {
                e.stopPropagation();
            }
            if (!filterPopover.classList.contains('d-none')) {
                filterPopover.classList.add('d-none');
            }

        });
    });
</script>
