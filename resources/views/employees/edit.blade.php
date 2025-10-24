@extends('layouts.app')

@section('title', 'Edit Employee - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-edit me-2"></i>Edit Employee #{{ $employee->id }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group">
            <a href="{{ route('employees.show', $employee) }}" class="btn btn-info">
                <i class="fas fa-eye me-1"></i>View Employee
            </a>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Employees
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Employee Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('employees.update', $employee) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" id="name" value="{{ old('name') ?? $employee->name }}" 
                                       placeholder="Enter employee's full name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                                <select class="form-select @error('position') is-invalid @enderror" 
                                        name="position" id="position" required>
                                    <option value="">Select Position</option>
                                    <option value="cashier" {{ (old('position') ?? $employee->position) == 'cashier' ? 'selected' : '' }}>Cashier</option>
                                    <option value="manager" {{ (old('position') ?? $employee->position) == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="clerk" {{ (old('position') ?? $employee->position) == 'clerk' ? 'selected' : '' }}>Clerk</option>
                                    <option value="accountant" {{ (old('position') ?? $employee->position) == 'accountant' ? 'selected' : '' }}>Accountant</option>
                                </select>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                                <select class="form-select @error('branch_id') is-invalid @enderror" 
                                        name="branch_id" id="branch_id" required>
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" 
                                                {{ (old('branch_id') ?? $employee->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }} - {{ $branch->city }}, {{ $branch->state }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="salary" class="form-label">Salary <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('salary') is-invalid @enderror" 
                                           name="salary" id="salary" value="{{ old('salary') ?? $employee->salary }}" 
                                           placeholder="0.00" step="0.01" min="0" required>
                                </div>
                                @error('salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Salary Guidelines -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i>Salary Guidelines</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small>
                                    <strong>Manager:</strong> $60,000 - $120,000<br>
                                    <strong>Accountant:</strong> $45,000 - $80,000
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small>
                                    <strong>Cashier:</strong> $25,000 - $40,000<br>
                                    <strong>Clerk:</strong> $30,000 - $45,000
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Current vs New Information -->
                    <div class="alert alert-secondary">
                        <h6><i class="fas fa-info-circle me-1"></i>Current Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small>
                                    <strong>Current Position:</strong> {{ ucfirst($employee->position) }}<br>
                                    <strong>Current Branch:</strong> {{ $employee->branch->name }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small>
                                    <strong>Current Salary:</strong> ${{ number_format($employee->salary, 2) }}<br>
                                    <strong>Hired Date:</strong> {{ $employee->created_at->format('M d, Y') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-suggest salary based on position (only if current salary is being changed)
    const positionSelect = document.getElementById('position');
    const salaryInput = document.getElementById('salary');
    const originalSalary = salaryInput.value;
    
    positionSelect.addEventListener('change', function() {
        const position = this.value;
        let suggestedSalary = '';
        
        switch(position) {
            case 'manager':
                suggestedSalary = '75000';
                break;
            case 'accountant':
                suggestedSalary = '55000';
                break;
            case 'cashier':
                suggestedSalary = '30000';
                break;
            case 'clerk':
                suggestedSalary = '35000';
                break;
        }
        
        // Only suggest if user hasn't modified the salary or confirm the change
        if (suggestedSalary && (salaryInput.value == originalSalary || confirm('Do you want to update the salary to the suggested amount for this position?'))) {
            salaryInput.value = suggestedSalary;
        }
    });
});
</script>
@endsection