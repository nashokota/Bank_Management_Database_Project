@extends('layouts.app')

@section('title', 'Employees - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-user-tie me-2"></i>Employee Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('employees.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Employee
        </a>
    </div>
</div>

<!-- Search -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search Employees</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('employees.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Employee Name</label>
                    <input type="text" class="form-control" name="search" id="search" 
                           value="{{ request('search') }}" placeholder="Enter employee name">
                </div>
                <div class="col-md-3">
                    <label for="position" class="form-label">Position</label>
                    <select class="form-select" name="position" id="position">
                        <option value="">All Positions</option>
                        <option value="cashier" {{ request('position') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                        <option value="manager" {{ request('position') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="clerk" {{ request('position') == 'clerk' ? 'selected' : '' }}>Clerk</option>
                        <option value="accountant" {{ request('position') == 'accountant' ? 'selected' : '' }}>Accountant</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select class="form-select" name="branch_id" id="branch_id">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label for="min_salary" class="form-label">Min Salary</label>
                    <input type="number" class="form-control" name="min_salary" id="min_salary" 
                           value="{{ request('min_salary') }}" placeholder="Minimum salary" step="0.01">
                </div>
                <div class="col-md-6">
                    <label for="max_salary" class="form-label">Max Salary</label>
                    <input type="number" class="form-control" name="max_salary" id="max_salary" 
                           value="{{ request('max_salary') }}" placeholder="Maximum salary" step="0.01">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Employees Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Employees List ({{ $employees->total() }} total)</h5>
    </div>
    <div class="card-body">
        @if($employees->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee</th>
                            <th>Position</th>
                            <th>Branch</th>
                            <th>Salary</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td><strong>#{{ $employee->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-{{ 
                                        $employee->position == 'manager' ? 'success' : 
                                        ($employee->position == 'accountant' ? 'info' : 
                                        ($employee->position == 'cashier' ? 'warning' : 'secondary'))
                                    }} rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-{{ 
                                            $employee->position == 'manager' ? 'crown' : 
                                            ($employee->position == 'accountant' ? 'calculator' : 
                                            ($employee->position == 'cashier' ? 'cash-register' : 'user'))
                                        }} text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $employee->name }}</div>
                                        <small class="text-muted">Employee since {{ $employee->created_at->format('Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $employee->position == 'manager' ? 'success' : 
                                    ($employee->position == 'accountant' ? 'info' : 
                                    ($employee->position == 'cashier' ? 'warning' : 'secondary'))
                                }}">
                                    {{ ucfirst($employee->position) }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $employee->branch->branch_name }}</strong>
                                </div>
                                <small class="text-muted">{{ $employee->branch->address }}</small>
                            </td>
                            <td>
                                <strong>${{ number_format($employee->salary, 2) }}</strong>
                            </td>
                            <td>
                                {{ $employee->created_at->format('M d, Y') }}
                                <br><small class="text-muted">{{ $employee->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this employee?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $employees->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-user-tie fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No employees found</h5>
                <p class="text-muted">Try adjusting your search criteria or add a new employee.</p>
                <a href="{{ route('employees.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add First Employee
                </a>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast show" role="alert">
            <div class="toast-header">
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

<style>
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
}
</style>
@endsection