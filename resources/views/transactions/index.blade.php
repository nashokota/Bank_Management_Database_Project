@extends('layouts.app')

@section('title', 'Transactions - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-exchange-alt me-2"></i>Transaction Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Transaction
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Transactions</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('transactions.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="transaction_type" class="form-label">Transaction Type</label>
                    <select class="form-select" name="transaction_type" id="transaction_type">
                        <option value="">All Types</option>
                        <option value="deposit" {{ request('transaction_type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                        <option value="withdraw" {{ request('transaction_type') == 'withdraw' ? 'selected' : '' }}>Withdrawal</option>
                        <option value="transfer" {{ request('transaction_type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="loan_payment" {{ request('transaction_type') == 'loan_payment' ? 'selected' : '' }}>Loan Payment</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="account_id" class="form-label">Account</label>
                    <select class="form-select" name="account_id" id="account_id">
                        <option value="">All Accounts</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                #{{ $account->id }} - {{ $account->customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" 
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" 
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="min_amount" class="form-label">Min Amount</label>
                    <input type="number" class="form-control" name="min_amount" id="min_amount" 
                           value="{{ request('min_amount') }}" step="0.01">
                </div>
                <div class="col-md-3">
                    <label for="max_amount" class="form-label">Max Amount</label>
                    <input type="number" class="form-control" name="max_amount" id="max_amount" 
                           value="{{ request('max_amount') }}" step="0.01">
                </div>
                <div class="col-md-6">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Transactions Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Transactions List ({{ $transactions->total() }} total)</h5>
    </div>
    <div class="card-body">
        @if($transactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date & Time</th>
                            <th>Account</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance After</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td><strong>#{{ $transaction->id }}</strong></td>
                            <td>
                                <div>{{ $transaction->date_time->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $transaction->date_time->format('H:i:s') }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">#{{ $transaction->account->id }}</div>
                                <small class="text-muted">{{ $transaction->account->branch->branch_name }}</small>
                            </td>
                            <td>
                                <div>{{ $transaction->account->customer->name }}</div>
                                <small class="text-muted">{{ $transaction->account->customer->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $transaction->transaction_type == 'deposit' ? 'success' : 
                                    ($transaction->transaction_type == 'withdraw' ? 'danger' : 
                                    ($transaction->transaction_type == 'transfer' ? 'info' : 'warning'))
                                }}">
                                    {{ ucwords(str_replace('_', ' ', $transaction->transaction_type)) }}
                                </span>
                                @if($transaction->transaction_type == 'transfer' && $transaction->destinationAccount)
                                    <br><small class="text-muted">
                                        To: {{ $transaction->destinationAccount->customer->name }}
                                    </small>
                                @elseif($transaction->transaction_type == 'deposit' && $transaction->transfer_reference)
                                    <br><small class="text-muted">
                                        Transfer received
                                    </small>
                                @endif
                            </td>
                            <td class="text-{{ 
                                in_array($transaction->transaction_type, ['deposit', 'loan_payment']) ? 'success' : 'danger' 
                            }}">
                                {{ in_array($transaction->transaction_type, ['deposit', 'loan_payment']) ? '+' : '-' }}
                                ${{ number_format((float)$transaction->amount, 2) }}
                            </td>
                            <td class="fw-bold">${{ number_format((float)$transaction->balance_after_transaction, 2) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Delete" 
                                            onclick="confirmDelete({{ $transaction->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                <h5>No Transactions Found</h5>
                <p class="text-muted">No transactions match your current filters.</p>
                <a href="{{ route('transactions.create') }}" class="btn btn-primary">Create First Transaction</a>
            </div>
        @endif
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
                <div class="alert alert-warning">
                    <strong>Warning:</strong> Deleting transactions is not recommended in banking systems!
                </div>
                <p>Are you sure you want to delete this transaction? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(transactionId) {
    const form = document.getElementById('deleteForm');
    form.action = `/transactions/${transactionId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush