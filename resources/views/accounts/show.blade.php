@extends('layouts.app')

@section('title', 'Account Details - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-wallet me-2"></i>Account Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('accounts.edit', $account) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Edit Account
            </a>
            <a href="{{ route('transactions.create') }}?account_id={{ $account->id }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>New Transaction
            </a>
        </div>
        <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Accounts
        </a>
    </div>
</div>

<div class="row">
    <!-- Account Information -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Account Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Account ID:</th>
                                <td><strong>#{{ $account->id }}</strong></td>
                            </tr>
                            <tr>
                                <th>Account Type:</th>
                                <td>
                                    <span class="badge bg-{{ 
                                        $account->account_type == 'savings' ? 'success' : 
                                        ($account->account_type == 'current' ? 'info' : 
                                        ($account->account_type == 'fixed_deposit' ? 'warning' : 'danger'))
                                    }}">
                                        {{ ucwords(str_replace('_', ' ', $account->account_type)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Balance:</th>
                                <td class="fw-bold text-{{ $account->balance >= 0 ? 'success' : 'danger' }} fs-5">
                                    ${{ number_format((float)$account->balance, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Date Opened:</th>
                                <td>{{ $account->date_opened->format('F d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-{{ $account->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($account->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Customer Information</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $account->customer->name }}</h5>
                                <p class="text-muted mb-0">{{ $account->customer->email }}</p>
                                <small class="text-muted">{{ $account->customer->phone }}</small>
                            </div>
                        </div>
                        
                        <h6 class="text-muted mb-3">Branch Information</h6>
                        <div class="d-flex align-items-center">
                            <div class="avatar-lg bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-building fa-2x text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $account->branch->branch_name }}</h6>
                                <p class="text-muted mb-0 small">{{ $account->branch->address }}</p>
                                <small class="text-muted">{{ $account->branch->phone }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Transactions</h5>
                <a href="{{ route('transactions.index') }}?account_id={{ $account->id }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @if($account->transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Balance After</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($account->transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->date_time->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $transaction->transaction_type == 'deposit' ? 'success' : 
                                            ($transaction->transaction_type == 'withdraw' ? 'danger' : 'primary')
                                        }}">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td class="text-{{ 
                                        in_array($transaction->transaction_type, ['deposit', 'loan_payment']) ? 'success' : 'danger' 
                                    }}">
                                        {{ in_array($transaction->transaction_type, ['deposit', 'loan_payment']) ? '+' : '-' }}
                                        ${{ number_format((float)$transaction->amount, 2) }}
                                    </td>
                                    <td>${{ number_format((float)$transaction->balance_after_transaction, 2) }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $transaction->description ?? 'No description' }}
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h6>No Transactions Found</h6>
                        <p class="text-muted">This account has no transaction history.</p>
                        <a href="{{ route('transactions.create') }}?account_id={{ $account->id }}" class="btn btn-primary btn-sm">
                            Create First Transaction
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Account Summary -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Account Summary</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-success mb-1">${{ number_format((float)$account->balance, 2) }}</h4>
                            <small class="text-muted">Current Balance</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info mb-1">{{ $account->transactions->count() }}</h4>
                        <small class="text-muted">Total Transactions</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-12">
                        <small class="text-muted d-block">Account opened</small>
                        <strong>{{ $account->date_opened->diffForHumans() }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('transactions.create') }}?account_id={{ $account->id }}&type=deposit" 
                       class="btn btn-success btn-sm">
                        <i class="fas fa-plus-circle me-1"></i>Make Deposit
                    </a>
                    <a href="{{ route('transactions.create') }}?account_id={{ $account->id }}&type=withdraw" 
                       class="btn btn-warning btn-sm">
                        <i class="fas fa-minus-circle me-1"></i>Make Withdrawal
                    </a>
                    <a href="{{ route('transactions.create') }}?account_id={{ $account->id }}&type=transfer" 
                       class="btn btn-info btn-sm">
                        <i class="fas fa-exchange-alt me-1"></i>Transfer Money
                    </a>
                    <a href="{{ route('accounts.edit', $account) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 48px;
    height: 48px;
}
</style>
@endsection