
             <div class="table-responsive">
                            @php
                                $groupedData = $groupedStats->groupBy(fn($item) =>
                                    optional($item['employee']->employementDetail->branch)->name ?? 'N/A'
                                )->map(function ($items) {
                                    return $items->groupBy(fn($item) =>
                                        optional($item['employee']->employementDetail->department)->name ?? 'N/A'
                                    );
                                });
                            @endphp

                            @foreach($groupedData as $branchName => $departments)
                                <table class="table table-bordered mb-4">
                                    <thead>
                                        <tr>
                                            <th colspan="10">Branch: {{ $branchName }}</th>
                                        </tr>
                                        <tr>
                                            <th>No.</th>
                                            <th>Emp ID</th>
                                            <th>Employee Name</th>
                                            <th>Designation</th>
                                            <th>Present Days</th>
                                            <th>Absent Days</th>
                                            <th>Late Days</th>
                                            <th>Leave Days</th>
                                            <th>Holiday Days</th>
                                            <th>Total Days</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($departments as $departmentName => $employees)
                                            <tr>
                                                <td colspan="10">Department: {{ $departmentName }}</td>
                                            </tr>
                                            @foreach($employees as $index => $report)
                                                @php
                                                    $employee = $report['employee'];
                                                    $employment = $employee->employementDetail;
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $employment->card_no ?? 'N/A' }}</td>
                                                    <td>{{ $employee->full_name }}</td>
                                                    <td>{{ $employment->designation->name ?? 'N/A' }}</td>
                                                    <td>{{ $report['present_days'] }}</td>
                                                    <td>{{ $report['absent_days'] }}</td>
                                                    <td>{{ $report['late_days'] }}</td>
                                                    <td>{{ $report['leave_days'] }}</td>
                                                    <td>{{ $report['holy_days'] }}</td>
                                                    <td>{{ $report['total_days'] + $report['late_days'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        </div>