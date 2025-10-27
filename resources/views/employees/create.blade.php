@extends('layouts.app')

@section('title', 'Add New Employee - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus me-2"></i>Add New Employee</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Employees
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Employee Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('employees.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" id="name" value="{{ old('name') }}" 
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
                                    <option value="cashier" {{ old('position') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                                    <option value="manager" {{ old('position') == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="clerk" {{ old('position') == 'clerk' ? 'selected' : '' }}>Clerk</option>
                                    <option value="accountant" {{ old('position') == 'accountant' ? 'selected' : '' }}>Accountant</option>
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
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->branch_name }} - {{ $branch->address }}
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
                                           name="salary" id="salary" value="{{ old('salary') }}" 
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

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Create Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-suggest salary based on position
    const positionSelect = document.getElementById('position');
    const salaryInput = document.getElementById('salary');
    
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
        
        if (suggestedSalary && !salaryInput.value) {
            salaryInput.value = suggestedSalary;
        }
    });
});
</script>
@endsection