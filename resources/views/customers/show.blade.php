@extends('layouts.app')

@section('title', 'Customer Details - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-user me-2"></i>Customer Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Edit Customer
            </a>
            <a href="{{ route('accounts.create') }}?customer_id={{ $customer->id }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>New Account
            </a>
        </div>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Customers
        </a>
    </div>
</div>

<div class="row">
    <!-- Customer Information -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Customer Information</h5>
            </div>
            <div class="card-body text-center">
                <div class="avatar-xl bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                    <i class="fas fa-user fa-3x text-white"></i>
                </div>
                <h4 class="mb-1">{{ $customer->name }}</h4>
                <p class="text-muted mb-3">Customer ID: #{{ $customer->id }}</p>
                
                <div class="text-start">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%"><i class="fas fa-envelope me-2 text-primary"></i>Email:</th>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-phone me-2 text-success"></i>Phone:</th>
                            <td>{{ $customer->phone }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-birthday-cake me-2 text-warning"></i>Date of Birth:</th>
                            <td>{{ $customer->dob->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-calendar me-2 text-info"></i>Age:</th>
                            <td>{{ $customer->dob->age }} years old</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-map-marker-alt me-2 text-danger"></i>Address:</th>
                            <td>{{ $customer->address }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-clock me-2 text-secondary"></i>Member Since:</th>
                            <td>{{ $customer->created_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Stats -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Customer Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary mb-1">{{ $customer->accounts->count() }}</h4>
                        <small class="text-muted">Total Accounts</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-1">${{ number_format($customer->accounts->sum('balance'), 2) }}</h4>
                        <small class="text-muted">Total Balance</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-warning mb-1">{{ $customer->loans->count() }}</h4>
                        <small class="text-muted">Total Loans</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info mb-1">{{ $customer->accounts->where('status', 'active')->count() }}</h4>
                        <small class="text-muted">Active Accounts</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accounts and Loans -->
    <div class="col-lg-8">
        <!-- Accounts -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-wallet me-2"></i>Customer Accounts</h5>
                <a href="{{ route('accounts.create') }}?customer_id={{ $customer->id }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Add Account
                </a>
            </div>
            <div class="card-body">
                @if($customer->accounts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Account ID</th>
                                    <th>Type</th>
                                    <th>Branch</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->accounts as $account)
                                <tr>
                                    <td><strong>#{{ $account->id }}</strong></td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $account->account_type == 'savings' ? 'success' : 
                                            ($account->account_type == 'current' ? 'info' : 
                                            ($account->account_type == 'fixed_deposit' ? 'warning' : 'danger'))
                                        }}">
                                            {{ ucwords(str_replace('_', ' ', $account->account_type)) }}
                                        </span>
                                    </td>
                                    <td>{{ $account->branch->branch_name }}</td>
                                    <td class="fw-bold text-{{ $account->balance >= 0 ? 'success' : 'danger' }}">
                                        ${{ number_format($account->balance, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $account->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($account->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('accounts.show', $account) }}" class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('accounts.edit', $account) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-wallet fa-3x text-muted mb-3"></i>
                        <h6>No Accounts Found</h6>
                        <p class="text-muted">This customer doesn't have any accounts yet.</p>
                        <a href="{{ route('accounts.create') }}?customer_id={{ $customer->id }}" class="btn btn-primary btn-sm">
                            Create First Account
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Loans -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-hand-holding-usd me-2"></i>Customer Loans</h5>
                <a href="{{ route('loans.create') }}?customer_id={{ $customer->id }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-plus me-1"></i>Add Loan
                </a>
            </div>
            <div class="card-body">
                @if($customer->loans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Loan ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Interest Rate</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->loans as $loan)
                                <tr>
                                    <td><strong>#{{ $loan->id }}</strong></td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($loan->loan_type) }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">${{ number_format($loan->loan_amount, 2) }}</td>
                                    <td>{{ $loan->interest_rate }}%</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $loan->status == 'ongoing' ? 'warning' : 
                                            ($loan->status == 'closed' ? 'success' : 'danger') 
                                        }}">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('loans.show', $loan) }}" class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('loans.edit', $loan) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-hand-holding-usd fa-3x text-muted mb-3"></i>
                        <h6>No Loans Found</h6>
                        <p class="text-muted">This customer doesn't have any loans.</p>
                        <a href="{{ route('loans.create') }}?customer_id={{ $customer->id }}" class="btn btn-warning btn-sm">
                            Apply for Loan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.avatar-xl {
    width: 80px;
    height: 80px;
}
</style>
@endsection