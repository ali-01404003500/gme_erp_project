@extends('layout.app')
@section('content')

<style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: white;
            border-bottom: 2px solid #f1f1f1;
            padding: 20px 25px;
        }
        .card-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }
        .card-body {
            padding: 25px;
        }
        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }
        .required::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 10px 15px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.15);
        }
        .btn {
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-outline-secondary {
            border: 1px solid #ccc;
            color: #555;
        }
        .btn-outline-secondary:hover {
            background-color: #f1f1f1;
            border-color: #aaa;
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
        }
        .footer-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
    </style>

<body>
    <div class="card">
        <div class="card-header">
            <h3>New Adjustment</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="#">
                @csrf

                  <!-- Leave Year -->
                <div class="mb-4">
                    <label for="leave_year" class="form-label required">Employee Name</label>
                    <select name="leave_year" id="leave_year" class="form-select" required>
                        <option value="">Select Employee</option>
                        <option value="employee">Rakib</option>
                        
                    </select>
                </div>

                <!-- Leave Year -->
                <div class="mb-4">
                    <label for="leave_year" class="form-label required">Leave Year</label>
                    <select name="leave_year" id="leave_year" class="form-select" required>
                        <option value="">Select Year</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025" selected>2025</option>
                        <option value="2026">2026</option>
                    </select>
                </div>

                <!-- Leave Type -->
                <div class="mb-4">
                    <label for="leave_type" class="form-label required">Leave Type</label>
                    <select name="leave_type" id="leave_type" class="form-select" required>
                        <option value="" selected disabled>Select Leave Type</option>
                        <option value="annual">Annual Leave</option>
                        <option value="sick">Sick Leave</option>
                        <option value="casual">Casual Leave</option>
                        <option value="maternity">Maternity Leave</option>
                        <option value="paternity">Paternity Leave</option>
                        <option value="unpaid">Unpaid Leave</option>
                    </select>
                </div>

                <!-- Adjusted Balance -->
                <div class="mb-4">
                    <label for="adjusted_balance" class="form-label required">Adjusted Balance</label>
                    <input type="number" name="adjusted_balance" id="adjusted_balance" class="form-control" value="0" step="0.01" min="0" required>
                </div>

                <!-- Remarks -->
                <div class="mb-4">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control" placeholder="---"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="footer-buttons">
                    <a href="{{ route('hrm.leaveAdjustment.index') }}" class="btn btn-outline-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (optional if you need dropdowns/modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection


