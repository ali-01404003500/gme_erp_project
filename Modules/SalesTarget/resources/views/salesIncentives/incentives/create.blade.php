@section('title', 'Incentive Setup')
@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Sales Incentive Slab Setup</h2>
            <a href="{{ route('sales_target.settings.incentives.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <form action="{{ route('sales_target.settings.incentives.store') }}" method="POST">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Incentive Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Annual Sales Bonus"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Incentive Year</label>
                            <select name="year" class="form-control">
                                @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="slab-table">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">Slab No</th>
                            <th>Min Range (%)</th>
                            <th>Max Range (%)</th>
                            <th>Incentive Type</th>
                            <th>Rate / Amount</th>
                            <th>Notes</th>
                            <th style="width: 50px;">
                                <button type="button" class="btn btn-sm btn-success" id="add-row">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="slab-row">
                            <td class="slab-index">1</td>
                            <td><input type="number" name="slabs[0][min]" class="form-control" required></td>
                            <td><input type="number" name="slabs[0][max]" class="form-control" required></td>
                            <td>
                                <select name="slabs[0][type]" class="form-control">
                                    <option value="No Incentive">No Incentive</option>
                                    <option value="Sales Incentive">Sales Incentive</option>
                                    <option value="High Performer Bonus">High Performer Bonus</option>
                                </select>
                            </td>
                            <td><input type="number" name="slabs[0][rate]" step="0.01" class="form-control" required></td>
                            <td><textarea name="slabs[0][notes]" class="form-control" rows="1"></textarea></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save"></i> Save Incentive Setup
                </button>
            </div>
        </form>
    </div>

    <script>
        let rowIdx = 1;

        // Add Row Logic
        document.getElementById('add-row').addEventListener('click', function () {
            let tableBody = document.querySelector('#slab-table tbody');
            let newRow = `
                <tr class="slab-row">
                    <td class="slab-index">${rowIdx + 1}</td>
                    <td><input type="number" name="slabs[${rowIdx}][min]" class="form-control" required></td>
                    <td><input type="number" name="slabs[${rowIdx}][max]" class="form-control" required></td>
                    <td>
                        <select name="slabs[${rowIdx}][type]" class="form-control">
                            <option value="No Incentive">No Incentive</option>
                            <option value="Sales Incentive">Sales Incentive</option>
                            <option value="High Performer Bonus">High Performer Bonus</option>
                        </select>
                    </td>
                    <td><input type="number" name="slabs[${rowIdx}][rate]" step="0.01" class="form-control" required></td>
                    <td><textarea name="slabs[${rowIdx}][notes]" class="form-control" rows="1"></textarea></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', newRow);
            rowIdx++;
            updateSlabNumbers();
        });

        // Delete Row Logic (Fixed)
        document.addEventListener('click', function (e) {
            // Check if the clicked element or its parent has the 'remove-row' class
            const removeBtn = e.target.closest('.remove-row');
            if (removeBtn) {
                removeBtn.closest('tr').remove();
                updateSlabNumbers();
            }
        });

        // Helper function to re-index the "Slab No" column
        function updateSlabNumbers() {
            const rows = document.querySelectorAll('.slab-row');
            rows.forEach((row, index) => {
                row.querySelector('.slab-index').innerText = index + 1;
            });
            // Update rowIdx to match current length for next addition
            rowIdx = rows.length;
        }
    </script>
@endsection