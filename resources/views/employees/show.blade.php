@extends('layouts.app')

@section('title', 'Employee Details - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-user-tie me-2"></i>Employee Details #{{ $employee->id }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Edit Employee
            </a>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Employees
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Employee Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="avatar-xl bg-{{ 
                            $employee->position == 'manager' ? 'success' : 
                            ($employee->position == 'accountant' ? 'info' : 
                            ($employee->position == 'cashier' ? 'warning' : 'secondary'))
                        }} rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-{{ 
                                $employee->position == 'manager' ? 'crown' : 
                                ($employee->position == 'accountant' ? 'calculator' : 
                                ($employee->position == 'cashier' ? 'cash-register' : 'user'))
                            }} fa-3x text-white"></i>
                        </div>
                        <h4>{{ $employee->name }}</h4>
                        <span class="badge bg-{{ 
                            $employee->position == 'manager' ? 'success' : 
                            ($employee->position == 'accountant' ? 'info' : 
                            ($employee->position == 'cashier' ? 'warning' : 'secondary'))
                        }} fs-6">
                            {{ ucfirst($employee->position) }}
                        </span>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Employee ID:</strong></td>
                                <td>#{{ $employee->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Full Name:</strong></td>
                                <td>{{ $employee->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Position:</strong></td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $employee->position == 'manager' ? 'success' : 
                                        ($employee->position == 'accountant' ? 'info' : 
                                        ($employee->position == 'cashier' ? 'warning' : 'secondary'))
                                    }}">
                                        {{ ucfirst($employee->position) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Salary:</strong></td>
                                <td><span class="h5 text-success">${{ number_format($employee->salary, 2) }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Date Hired:</strong></td>
                                <td>{{ $employee->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Years of Service:</strong></td>
                                <td>{{ $employee->created_at->diffForHumans() }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $employee->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-building me-2"></i>Branch Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-lg bg-primary rounded-circle d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-building fa-2x text-white"></i>
                    </div>
                </div>
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Branch:</strong></td>
                        <td>{{ $employee->branch->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Address:</strong></td>
                        <td>{{ $employee->branch->address }}</td>
                    </tr>
                    <tr>
                        <td><strong>City:</strong></td>
                        <td>{{ $employee->branch->city }}</td>
                    </tr>
                    <tr>
                        <td><strong>State:</strong></td>
                        <td>{{ $employee->branch->state }}</td>
                    </tr>
                    <tr>
                        <td><strong>ZIP Code:</strong></td>
                        <td>{{ $employee->branch->zip_code }}</td>
                    </tr>
                </table>
                <div class="text-center">
                    <a href="{{ route('branches.show', $employee->branch) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>View Branch
                    </a>
                </div>
            </div>
        </div>

        @php
            $monthlyGross = $employee->salary / 12;
            $monthlyNet = $monthlyGross * 0.75; // Assuming 25% deductions
            $weeklyGross = $employee->salary / 52;
        @endphp

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Salary Breakdown</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Annual Salary:</strong></td>
                        <td class="text-end">${{ number_format($employee->salary, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Monthly Gross:</strong></td>
                        <td class="text-end">${{ number_format($monthlyGross, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Monthly Net:</strong></td>
                        <td class="text-end text-success">${{ number_format($monthlyNet, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Weekly Gross:</strong></td>
                        <td class="text-end">${{ number_format($weeklyGross, 2) }}</td>
                    </tr>
                </table>
                <small class="text-muted">
                    * Net salary assumes 25% total deductions (taxes, benefits, etc.)
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Performance Metrics -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Performance Metrics</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <div class="border-end">
                    <h4 class="text-primary mb-1">{{ $employee->created_at->diffInYears() }}</h4>
                    <small class="text-muted">Years of Service</small>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="border-end">
                    <h4 class="text-success mb-1">${{ number_format($employee->salary, 0) }}</h4>
                    <small class="text-muted">Annual Salary</small>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="border-end">
                    <h4 class="text-info mb-1">{{ ucfirst($employee->position) }}</h4>
                    <small class="text-muted">Current Position</small>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <h4 class="text-warning mb-1">{{ $employee->branch->name }}</h4>
                <small class="text-muted">Work Location</small>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Actions</h5>
    </div>
    <div class="card-body">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Edit Employee
            </a>
            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" 
                        onclick="return confirm('Are you sure you want to delete this employee? This action cannot be undone.')">
                    <i class="fas fa-trash me-1"></i>Delete Employee
                </button>
            </form>
        </div>
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
.avatar-lg {
    width: 4rem;
    height: 4rem;
}
.avatar-xl {
    width: 5rem;
    height: 5rem;
}
</style>
@endsection