
@extends('layout.app')

@section('title', 'Edit Salary Signatory')
@section('description', 'Edit Salary Signatory')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Salary Signatory</h3>
                        <a href="{{ route('hrm.salary-signatories.index') }}" class="btn btn-secondary float-right">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <form action="{{ route('hrm.salary-signatories.update', $salarySignatory) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user_id">Employee <span class="text-danger">*</span></label>
                                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                            <option value="">Select Employee</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id', $salarySignatory->user_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="signatory_tag">Signatory Tag <span class="text-danger">*</span></label>
                                        <input type="text" name="signatory_tag" id="signatory_tag" 
                                            class="form-control @error('signatory_tag') is-invalid @enderror"
                                            value="{{ old('signatory_tag', $salarySignatory->signatory_tag) }}" required>
                                        @error('signatory_tag')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="level">Approval Level <span class="text-danger">*</span></label>
                                        <select name="level" id="level" class="form-control @error('level') is-invalid @enderror" required>
                                            <option value="">Select Level</option>
                                            @foreach($levels as $level)
                                                <option value="{{ $level }}" {{ old('level', $salarySignatory->level) == $level ? 'selected' : '' }}>
                                                    Level {{ $level }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('level')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="active" {{ old('status', $salarySignatory->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $salarySignatory->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" 
                                            class="form-control @error('description') is-invalid @enderror"
                                            rows="3">{{ old('description', $salarySignatory->description) }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Signatory
                            </button>
                            <a href="{{ route('hrm.salary-signatories.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection