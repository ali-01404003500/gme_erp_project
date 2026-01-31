@extends('pdf-templates.basic-template', [
    'title' => 'Salary Setup',
    'subtitles' => 'List',
    'table' => ""
])

@push('table')
    <table id="zero-config" width="100%" >
        <thead>
            <tr>
                <th width="5%">Sl</th>
                <th width="20%">Title</th>
                <th width="15%">Effective Date</th>
                <th width="10%">Basic(%)</th>
                <th width="10%">House Rent(%)</th>
                <th width="10%">Conveyance(% / Tk.)</th>
                <th width="10%">Medical(% / Tk.)</th>
                <th width="10%">Others(% / Tk.)</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($salarySetups as $value)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ $value->title }}
                    </td>
                    <td>{{ $value->effective_date }}</td>
                    <td>{{ number_format($value->basic) }}</td>
                    <td>{{  number_format($value->house_rent) }}</td>
                    <td>{{  number_format($value->conveyance) }}</td>
                    <td>{{  number_format($value->medical) }}</td>
                    <td>{{  number_format($value->others) }}</td>
                    <td>
                        @if ($value->status == '0')
                            <span class="badge badge-round badge-warning">De-Active</span>
                        @elseif($value->status == '1')
                            <span class="badge badge-round badge-success">Active</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endpush