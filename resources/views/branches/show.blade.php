@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Branch Details</h4>
                    <div>
                        <a href="{{ route('branches.edit', $branch) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('branches.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h5 class="card-title">{{ $branch->branch_name }}</h5>
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="badge badge-success badge-lg">Active</span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6><i class="fas fa-map-marker-alt"></i> Address</h6>
                            <p>{{ $branch->address }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-phone"></i> Contact Information</h6>
                            <p><strong>Phone:</strong> {{ $branch->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-clock"></i> Timestamps</h6>
                            <p><strong>Created:</strong> {{ $branch->created_at->format('M d, Y H:i') }}</p>
                            <p><strong>Updated:</strong> {{ $branch->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Branch Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $branch->accounts->count() }}</h5>
                                    <p>Total Accounts</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $branch->employees->count() }}</h5>
                                    <p>Employees</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5>${{ number_format($branch->accounts->sum('balance'), 2) }}</h5>
                                    <p>Total Balance</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accounts Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="fas fa-university"></i> Accounts ({{ $filteredAccounts->count() }})</h5>
                            
                            <!-- Accounts Filter -->
                            <form method="GET" action="{{ route('branches.show', $branch) }}" class="d-flex align-items-center">
                                @if(request('employee_position'))
                                    <input type="hidden" name="employee_position" value="{{ request('employee_position') }}">
                                @endif
                                <select name="account_type" class="form-select form-select-sm me-2" style="width: auto;" onchange="this.form.submit()">
                                    <option value="">All Account Types</option>
                                    @foreach($accountTypes as $type)
                                        <option value="{{ $type }}" {{ request('account_type') == $type ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(request('account_type'))
                                    <a href="{{ route('branches.show', $branch) }}{{ request('employee_position') ? '?employee_position=' . request('employee_position') : '' }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                        @if($filteredAccounts->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-dark">Account Number</th>
                                            <th class="text-dark">Customer</th>
                                            <th class="text-dark">Account Type</th>
                                            <th class="text-dark">Balance</th>
                                            <th class="text-dark">Status</th>
                                            <th class="text-dark">Opened</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($filteredAccounts as $account)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('accounts.show', $account) }}" class="text-decoration-none">
                                                        ACC-{{ str_pad($account->id, 6, '0', STR_PAD_LEFT) }}
                                                    </a>
                                                </td>
                                                <td>{{ $account->customer->name }}</td>
                                                <td>
                                                    <span class="badge bg-secondary text-white">{{ ucfirst(str_replace('_', ' ', $account->account_type)) }}</span>
                                                </td>
                                                <td>${{ number_format($account->balance, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $account->status === 'active' ? 'success' : 'secondary' }} text-white">
                                                        {{ ucfirst($account->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($account->date_opened)
                                                        {{ \Carbon\Carbon::parse($account->date_opened)->format('M d, Y') }}
                                                    @else
                                                        {{ $account->created_at->format('M d, Y') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">
                                @if(request('account_type'))
                                    No accounts found with type "{{ ucfirst(str_replace('_', ' ', request('account_type'))) }}" in this branch.
                                @else
                                    No accounts found for this branch.
                                @endif
                            </p>
                        @endif
                    </div>

                    <!-- Employees Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="fas fa-users"></i> Employees ({{ $filteredEmployees->count() }})</h5>
                            
                            <!-- Employees Filter -->
                            <form method="GET" action="{{ route('branches.show', $branch) }}" class="d-flex align-items-center">
                                @if(request('account_type'))
                                    <input type="hidden" name="account_type" value="{{ request('account_type') }}">
                                @endif
                                <select name="employee_position" class="form-select form-select-sm me-2" style="width: auto;" onchange="this.form.submit()">
                                    <option value="">All Positions</option>
                                    @foreach($employeePositions as $position)
                                        <option value="{{ $position }}" {{ request('employee_position') == $position ? 'selected' : '' }}>
                                            {{ ucfirst($position) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(request('employee_position'))
                                    <a href="{{ route('branches.show', $branch) }}{{ request('account_type') ? '?account_type=' . request('account_type') : '' }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                        @if($filteredEmployees->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-dark">Employee ID</th>
                                            <th class="text-dark">Name</th>
                                            <th class="text-dark">Position</th>
                                            <th class="text-dark">Salary</th>
                                            <th class="text-dark">Hire Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($filteredEmployees as $employee)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('employees.show', $employee) }}" class="text-decoration-none">
                                                        EMP-{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}
                                                    </a>
                                                </td>
                                                <td>{{ $employee->name }}</td>
                                                <td>
                                                    <span class="badge bg-info text-white">{{ ucfirst($employee->position) }}</span>
                                                </td>
                                                <td>${{ number_format($employee->salary, 2) }}</td>
                                                <td>
                                                    {{ $employee->created_at->format('M d, Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">
                                @if(request('employee_position'))
                                    No employees found with position "{{ ucfirst(request('employee_position')) }}" in this branch.
                                @else
                                    No employees found for this branch.
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection