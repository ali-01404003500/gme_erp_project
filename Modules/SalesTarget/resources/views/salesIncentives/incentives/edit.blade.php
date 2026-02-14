@section('title', 'Edit Incentive')
@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <h2>Edit Sales Incentive Slab</h2>
        <form action="{{ route('sales_target.settings.incentives.update', $incentive->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Title</label>
                    <input type="text" name="title" value="{{ $incentive->title }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Year</label>
                    <select name="year" class="form-control">
                        @for($i = date('Y') - 1; $i <= date('Y') + 10; $i++)
                            <option value="{{ $i }}" {{ $incentive->year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Active" {{ $incentive->status == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ $incentive->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <table class="table table-bordered" id="slab-table">
                <thead>
                    <tr>
                        <th>Slab No</th>
                        <th>Min</th>
                        <th>Max</th>
                        <th>Type</th>
                        <th>Rate</th>
                        <th>Notes</th>
                        <th><button type="button" class="btn btn-sm btn-success" id="add-row">Add</button></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($slabs as $index => $slab)
                        <tr class="slab-row">
                            <td class="slab-index">{{ $index + 1 }}</td>
                            <td><input type="number" name="slabs[{{ $index }}][min]" value="{{ $slab->min_range }}"
                                    class="form-control"></td>
                            <td><input type="number" name="slabs[{{ $index }}][max]" value="{{ $slab->max_range }}"
                                    class="form-control"></td>
                            <td>
                                <select name="slabs[{{ $index }}][type]" class="form-control">
                                    <option value="No Incentive" {{ $slab->incentive_type == 'No Incentive' ? 'selected' : '' }}>
                                        No Incentive</option>
                                    <option value="Sales Incentive" {{ $slab->incentive_type == 'Sales Incentive' ? 'selected' : '' }}>Sales Incentive</option>
                                    <option value="High Performer Bonus" {{ $slab->incentive_type == 'High Performer Bonus' ? 'selected' : '' }}>High Performer Bonus</option>
                                </select>
                            </td>
                            <td><input type="number" name="slabs[{{ $index }}][rate]" value="{{ $slab->incentive_rate }}"
                                    step="0.01" class="form-control"></td>
                            <td><textarea name="slabs[{{ $index }}][notes]" class="form-control"
                                    rows="1">{{ $slab->notes }}</textarea></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Update Setup</button>
        </form>
    </div>

    <script>
        let rowIdx = {{ count($slabs) }};
        document.getElementById('add-row').addEventListener('click', function () {
            let tableBody = document.querySelector('#slab-table tbody');
            let newRow = `<tr class="slab-row">
                <td class="slab-index">${rowIdx + 1}</td>
                <td><input type="number" name="slabs[${rowIdx}][min]" class="form-control"></td>
                <td><input type="number" name="slabs[${rowIdx}][max]" class="form-control"></td>
                <td><select name="slabs[${rowIdx}][type]" class="form-control">
                    <option value="No Incentive">No Incentive</option>
                    <option value="Sales Incentive">Sales Incentive</option>
                    <option value="High Performer Bonus">High Performer Bonus</option>
                </select></td>
                <td><input type="number" name="slabs[${rowIdx}][rate]" step="0.01" class="form-control"></td>
                <td><textarea name="slabs[${rowIdx}][notes]" class="form-control" rows="1"></textarea></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
            </tr>`;
            tableBody.insertAdjacentHTML('beforeend', newRow);
            rowIdx++;
            updateNumbers();
        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
                updateNumbers();
            }
        });

        function updateNumbers() {
            document.querySelectorAll('.slab-row').forEach((row, i) => {
                row.querySelector('.slab-index').innerText = i + 1;
            });
        }
    </script>
@endsection