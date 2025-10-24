@extends('layouts.app')

@section('title', 'Transaction #' . $transaction->id . ' - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-receipt me-2"></i>Transaction #{{ $transaction->id }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Edit Transaction
            </a>
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                <i class="fas fa-trash me-1"></i>Delete
            </button>
        </div>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Transactions
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Transaction Details -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Transaction Details
                    <span class="badge bg-{{ 
                        $transaction->transaction_type == 'deposit' ? 'success' : 
                        ($transaction->transaction_type == 'withdraw' ? 'danger' : 
                        ($transaction->transaction_type == 'transfer' ? 'info' : 'warning'))
                    }} ms-2">
                        {{ ucwords(str_replace('_', ' ', $transaction->transaction_type)) }}
                    </span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-muted small">Transaction ID</label>
                        <div class="h5">#{{ $transaction->id }}</div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="text-muted small">Date & Time</label>
                        <div class="fw-bold">{{ $transaction->date_time->format('F j, Y') }}</div>
                        <div class="text-muted">{{ $transaction->date_time->format('g:i A') }}</div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="text-muted small">Amount</label>
                        <div class="h4 text-{{ 
                            in_array($transaction->transaction_type, ['deposit', 'loan_payment']) ? 'success' : 'danger' 
                        }}">
                            {{ in_array($transaction->transaction_type, ['deposit', 'loan_payment']) ? '+' : '-' }}
                            ${{ number_format((float)$transaction->amount, 2) }}
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="text-muted small">Balance After Transaction</label>
                        <div class="h5 text-primary">${{ number_format((float)$transaction->balance_after_transaction, 2) }}</div>
                    </div>
                    
                    @if($transaction->description)
                    <div class="col-12">
                        <label class="text-muted small">Description</label>
                        <div class="border rounded p-3 bg-light">{{ $transaction->description }}</div>
                    </div>
                    @endif
                    
                    <div class="col-12">
                        <label class="text-muted small">Created</label>
                        <div>{{ $transaction->created_at->format('F j, Y g:i A') }}</div>
                        @if($transaction->updated_at != $transaction->created_at)
                            <div class="text-muted small">Last updated: {{ $transaction->updated_at->format('F j, Y g:i A') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Transactions (if any) -->
        @if($relatedTransactions->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-link me-2"></i>Related Transactions</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($relatedTransactions as $related)
                            <tr>
                                <td>#{{ $related->id }}</td>
                                <td>{{ $related->date_time->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucwords(str_replace('_', ' ', $related->transaction_type)) }}
                                    </span>
                                </td>
                                <td>${{ number_format((float)$related->amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('transactions.show', $related) }}" class="btn btn-sm btn-outline-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Account Information Sidebar -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-university me-2"></i>Account Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Account Number</label>
                    <div class="fw-bold">#{{ $transaction->account->id }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Account Type</label>
                    <div>
                        <span class="badge bg-info">{{ ucwords($transaction->account->account_type) }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Current Balance</label>
                    <div class="h5 text-success">${{ number_format((float)$transaction->account->balance, 2) }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Account Status</label>
                    <div>
                        <span class="badge bg-{{ $transaction->account->status == 'active' ? 'success' : 'warning' }}">
                            {{ ucwords($transaction->account->status) }}
                        </span>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <label class="text-muted small">Customer</label>
                    <div class="fw-bold">{{ $transaction->account->customer->name }}</div>
                    <div class="text-muted small">{{ $transaction->account->customer->email }}</div>
                    <div class="text-muted small">{{ $transaction->account->customer->phone }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Branch</label>
                    <div class="fw-bold">{{ $transaction->account->branch->branch_name }}</div>
                    <div class="text-muted small">{{ $transaction->account->branch->address }}</div>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('accounts.show', $transaction->account) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>View Account
                    </a>
                    <a href="{{ route('customers.show', $transaction->account->customer) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-user me-1"></i>View Customer
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Transaction Impact -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Transaction Impact</h6>
            </div>
            <div class="card-body">
                @php
                    $balanceBefore = (float)$transaction->balance_after_transaction;
                    if (in_array($transaction->transaction_type, ['deposit', 'loan_payment'])) {
                        $balanceBefore -= (float)$transaction->amount;
                    } else {
                        $balanceBefore += (float)$transaction->amount;
                    }
                @endphp
                
                <div class="mb-3">
                    <label class="text-muted small">Balance Before</label>
                    <div class="fw-bold">${{ number_format($balanceBefore, 2) }}</div>
                </div>
                
                <div class="text-center my-2">
                    <i class="fas fa-arrow-down text-muted"></i>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Transaction</label>
                    <div class="text-{{ 
                        in_array($transaction->transaction_type, ['deposit', 'loan_payment']) ? 'success' : 'danger' 
                    }}">
                        {{ in_array($transaction->transaction_type, ['deposit', 'loan_payment']) ? '+' : '-' }}
                        ${{ number_format((float)$transaction->amount, 2) }}
                    </div>
                </div>
                
                <div class="text-center my-2">
                    <i class="fas fa-equals text-muted"></i>
                </div>
                
                <div>
                    <label class="text-muted small">Balance After</label>
                    <div class="h6 text-primary">${{ number_format((float)$transaction->balance_after_transaction, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <strong>Danger:</strong> Deleting transactions is not recommended in banking systems!
                </div>
                <p>Are you sure you want to delete transaction #{{ $transaction->id }}? This action cannot be undone and may affect account balance integrity.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Transaction</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush