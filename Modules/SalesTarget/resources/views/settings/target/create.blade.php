@section('title', 'Sales Target Entry')
@extends('layout.app')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .bg-canvas {
            padding: 2rem 1rem;
        }

        .matrix-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .header-gradient {
            background: #fdfdfd;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #eee;
        }


        .matrix-table {
            width: 100%;
            border-collapse: collapse;
        }

        .matrix-table thead th {
            background: #f1f5f9;
            color: #475569;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 12px;
            border: 1px solid #e2e8f0;
        }

        .matrix-table td {
            border: 1px solid #f1f5f9;
            padding: 0;
        }


        .input-flat {
            border: none;
            width: 100%;
            height: 45px;
            text-align: center;
            outline: none;
            background: transparent;
        }

        .input-flat:focus {
            background: #f0f7ff;
        }

        .row-total {
            background: #eef2ff !important;
            font-weight: bold;
            color: #4338ca;
        }


        .select2-container--default .select2-selection--single {
            border: none !important;
            height: 45px !important;
            display: flex;
            align-items: center;
        }

        .select2-container {
            width: 100% !important;
        }

        .btn-add {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-add:hover {
            background: #4338ca;
        }

        .remove-row-btn {
            color: #cbd5e1;
            cursor: pointer;
            border: none;
            background: none;
        }

        .remove-row-btn:hover {
            color: #ef4444;
        }
    </style>

    <div class="bg-canvas">
        <div class="matrix-card">
            <div class="header-gradient d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold">Target Configuration</h5>
                </div>
                <button type="button" class="btn-add" id="add-row">
                    <i class="bi bi-plus-lg me-1"></i> Add Employee
                </button>
            </div>

            <form action="{{ route('sales_target.settings.target.store') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="matrix-table" id="target-matrix">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="min-width: 250px;">Team Member</th>
                                <th style="width: 100px;">Year</th>
                                @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $m)
                                    <th>{{ $m }}</th>
                                @endforeach
                                <th style="width: 120px;">Total</th>
                                <th style="width: 50px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="matrix-body">
                           @if($employees->isEmpty())
                                <tr class="target-row">
                                    <td class="text-center sl-no">1</td>
                                    <td>
                                        <select name="targets[0][employee_id]" class="employee-search" required>
                                            <option value="">Search Employee...</option>
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                   <td>
                                        <input type="number" name="targets[0][year]" class="input-flat" value="{{ date('Y') }}">
                                    </td>
                                    @foreach(['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] as $m)
                                        <td>
                                            <input type="number" step="1000" name="targets[0][{{$m}}_target]" class="input-flat month-input" value="0">
                                        </td>
                                    @endforeach
                                    <td>
                                        <input type="number" name="targets[0][total_target]" class="input-flat row-total" value="0" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="remove-row-btn remove-row"><i class="bi bi-trash3-fill"></i></button>
                                    </td>
                                </tr> 
                            @else
                                @foreach($employees as $key => $emp)
                                    <tr class="target-row">
                                        <td class="text-center sl-no">{{ $key + 1 }}</td>
                                        <td>
                                            <span>{{ $emp->full_name }}</span><br>
                                            <span>{{  $emp->employementDetail->designation->name }} of <br>{{  $emp->employementDetail->department->name }}</span> 
                                            <input type="hidden" name="targets[{{ $key }}][employee_id]" class="form-control"  value="{{$emp->id}}"> 
                                        </td>
                                        <td>
                                            <input type="number" name="targets[{{ $key }}][year]" class="input-flat" value="{{ date('Y') }}">
                                        </td>
                                        @foreach(['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] as $m)
                                            <td>
                                                <input type="number" step="1000" name="targets[{{ $key }}][{{$m}}_target]" class="input-flat month-input" value="0">
                                            </td>
                                        @endforeach
                                        <td>
                                            <input type="number" name="targets[{{ $key }}][total_target]" class="input-flat row-total" value="0" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="remove-row-btn remove-row"><i class="bi bi-trash3-fill"></i></button>
                                        </td>
                                    </tr> 
                                @endforeach
                            @endif    
                           
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-top d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-5 fw-bold">
                        <i class="bi bi-check2-circle me-2"></i> Save All Targets
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {

            function initSelect2(element) {
                $(element).select2({
                    placeholder: "Search Employee...",
                    allowClear: true,
                    width: '100%'
                });
            }


            initSelect2('.employee-search');

            let rowIndex = 1;

            $('#add-row').click(function () {

                $('.employee-search').select2('destroy');

                const row = $('.target-row').first().clone();


                row.find('input').val(0);
                row.find('.row-total').val(0);
                row.find('input[name*="year"]').val("{{ date('Y') }}");
                row.find('select').val('').trigger('change');


                row.find('[name]').each(function () {
                    let name = $(this).attr('name');
                    $(this).attr('name', name.replace(/\[\d+\]/, '[' + rowIndex + ']'));
                });

                $('#matrix-body').append(row);


                initSelect2('.employee-search');

                updateSL();
                rowIndex++;
            });

            $(document).on('click', '.remove-row', function () {
                if ($('.target-row').length > 1) {
                    $(this).closest('.target-row').remove();
                    updateSL();
                }
            });

            $(document).on('input', '.month-input', function () {
                let row = $(this).closest('.target-row');
                let sum = 0;
                row.find('.month-input').each(function () {
                    sum += parseFloat($(this).val()) || 0;
                });
                row.find('.row-total').val(sum.toFixed(2));
            });

            function updateSL() {
                $('.sl-no').each(function (i) {
                    $(this).text(i + 1);
                });
            }
        });
    </script>
@endsection