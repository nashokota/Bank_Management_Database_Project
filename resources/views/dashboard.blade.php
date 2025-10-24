@extends('layouts.app')

@section('title', 'Dashboard - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Customers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Customer::count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Active Accounts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Account::where('status', 'active')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-wallet fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Balance</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format((float)\App\Models\Account::sum('balance') ?: 0, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Active Loans</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Loan::where('status', 'ongoing')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hand-holding-usd fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('customers.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus"></i><br>Add Customer
                        </a>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('accounts.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-plus-circle"></i><br>New Account
                        </a>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('transactions.create') }}" class="btn btn-info w-100">
                            <i class="fas fa-exchange-alt"></i><br>New Transaction
                        </a>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('loans.create') }}" class="btn btn-warning w-100">
                            <i class="fas fa-handshake"></i><br>New Loan
                        </a>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('branches.create') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-building"></i><br>Add Branch
                        </a>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('employees.create') }}" class="btn btn-dark w-100">
                            <i class="fas fa-user-tie"></i><br>Add Employee
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Transactions</h5>
            </div>
            <div class="card-body">
                @php
                    $recentTransactions = \App\Models\Transaction::with(['account.customer'])
                        ->orderBy('date_time', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->date_time->format('M d, Y H:i') }}</td>
                                    <td>{{ $transaction->account->customer->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $transaction->transaction_type == 'deposit' ? 'success' : 
                                            ($transaction->transaction_type == 'withdraw' ? 'danger' : 'primary')
                                        }}">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td>${{ number_format((float)$transaction->amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary btn-sm">View All Transactions</a>
                    </div>
                @else
                    <p class="text-muted text-center py-4">No transactions found.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Account Types Distribution</h5>
            </div>
            <div class="card-body">
                @php
                    $accountTypes = \App\Models\Account::selectRaw('account_type, count(*) as count')
                        ->groupBy('account_type')
                        ->get();
                @endphp
                
                @if($accountTypes->count() > 0)
                    @foreach($accountTypes as $type)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-capitalize">{{ str_replace('_', ' ', $type->account_type) }}</span>
                        <span class="badge bg-primary">{{ $type->count }}</span>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar" style="width: {{ ($type->count / \App\Models\Account::count()) * 100 }}%"></div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-4">No account data available.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-primary {
    color: #4e73df !important;
}
.text-success {
    color: #1cc88a !important;
}
.text-info {
    color: #36b9cc !important;
}
.text-warning {
    color: #f6c23e !important;
}
</style>
@endsection