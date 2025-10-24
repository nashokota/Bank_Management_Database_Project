@extends('layouts.app')

@section('title', 'Edit Transaction #' . $transaction->id . ' - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-edit me-2"></i>Edit Transaction #{{ $transaction->id }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-info">
                <i class="fas fa-eye me-1"></i>View Transaction
            </a>
        </div>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Transactions
        </a>
    </div>
</div>

<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Warning:</strong> Editing transactions in banking systems should be done carefully. 
    Changes will affect account balances and financial records.
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Transaction Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Account Selection (Read-only for integrity) -->
                        <div class="col-md-6">
                            <label for="account_id" class="form-label">Account <span class="text-danger">*</span></label>
                            <select class="form-select" name="account_id" id="account_id" disabled>
                                <option value="{{ $transaction->account->id }}" selected>
                                    #{{ $transaction->account->id }} - {{ $transaction->account->customer->name }}
                                </option>
                            </select>
                            <input type="hidden" name="account_id" value="{{ $transaction->account->id }}">
                            <div class="form-text">Account cannot be changed for existing transactions</div>
                        </div>

                        <!-- Transaction Type (Limited editing) -->
                        <div class="col-md-6">
                            <label for="transaction_type" class="form-label">Transaction Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('transaction_type') is-invalid @enderror" 
                                    name="transaction_type" id="transaction_type" required onchange="updateAmountLabel()">
                                <option value="deposit" {{ old('transaction_type', $transaction->transaction_type) == 'deposit' ? 'selected' : '' }}>
                                    Deposit
                                </option>
                                <option value="withdraw" {{ old('transaction_type', $transaction->transaction_type) == 'withdraw' ? 'selected' : '' }}>
                                    Withdrawal
                                </option>
                                <option value="transfer" {{ old('transaction_type', $transaction->transaction_type) == 'transfer' ? 'selected' : '' }}>
                                    Transfer
                                </option>
                                <option value="loan_payment" {{ old('transaction_type', $transaction->transaction_type) == 'loan_payment' ? 'selected' : '' }}>
                                    Loan Payment
                                </option>
                            </select>
                            @error('transaction_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6">
                            <label for="amount" class="form-label">
                                <span id="amountLabel">Amount</span> <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       name="amount" 
                                       id="amount" 
                                       value="{{ old('amount', $transaction->amount) }}" 
                                       step="0.01" 
                                       min="0.01"
                                       required
                                       oninput="calculateNewBalance()">
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Original amount: ${{ number_format((float)$transaction->amount, 2) }}
                            </div>
                        </div>

                        <!-- Date & Time -->
                        <div class="col-md-6">
                            <label for="date_time" class="form-label">Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" 
                                   class="form-control @error('date_time') is-invalid @enderror" 
                                   name="date_time" 
                                   id="date_time" 
                                   value="{{ old('date_time', $transaction->date_time->format('Y-m-d\TH:i')) }}" 
                                   required>
                            @error('date_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" 
                                      id="description" 
                                      rows="3" 
                                      placeholder="Optional transaction description">{{ old('description', $transaction->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update Transaction
                                </button>
                                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Account Summary Sidebar -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Account Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Customer</label>
                    <div class="fw-bold">{{ $transaction->account->customer->name }}</div>
                    <div class="text-muted small">{{ $transaction->account->customer->email }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Branch</label>
                    <div>{{ $transaction->account->branch->branch_name }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Current Account Balance</label>
                    <div class="h5 text-primary">${{ number_format((float)$transaction->account->balance, 2) }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">Original Transaction Balance</label>
                    <div class="fw-bold">${{ number_format((float)$transaction->balance_after_transaction, 2) }}</div>
                </div>

                <div id="balanceImpact" style="display: none;" class="mt-3 p-3 border rounded">
                    <h6 class="text-warning">Balance Impact</h6>
                    <div class="small">
                        <div>Current Balance: $<span id="currentBalance">{{ number_format((float)$transaction->account->balance, 2) }}</span></div>
                        <div>Adjustment: <span id="balanceAdjustment"></span></div>
                        <div class="fw-bold">New Balance: $<span id="newBalance"></span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Log -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Transaction History</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <label class="text-muted small">Created</label>
                    <div class="small">{{ $transaction->created_at->format('M j, Y g:i A') }}</div>
                </div>
                
                @if($transaction->updated_at != $transaction->created_at)
                <div class="mb-2">
                    <label class="text-muted small">Last Updated</label>
                    <div class="small">{{ $transaction->updated_at->format('M j, Y g:i A') }}</div>
                </div>
                @endif
                
                <div class="alert alert-info small mt-3">
                    <strong>Note:</strong> All changes are logged for audit purposes.
                </div>
            </div>
        </div>

        <!-- Guidelines -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Edit Guidelines</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li class="mb-1"><i class="fas fa-lock text-warning me-2"></i>Account cannot be changed</li>
                    <li class="mb-1"><i class="fas fa-edit text-info me-2"></i>Amount changes affect balance</li>
                    <li class="mb-1"><i class="fas fa-calendar text-primary me-2"></i>Date can be adjusted</li>
                    <li class="mb-1"><i class="fas fa-tag text-success me-2"></i>Type can be changed carefully</li>
                    <li class="mb-1"><i class="fas fa-shield-alt text-danger me-2"></i>All edits are audited</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const originalAmount = {{ $transaction->amount }};
const originalType = '{{ $transaction->transaction_type }}';
const currentBalance = {{ $transaction->account->balance }};

function updateAmountLabel() {
    const transactionType = document.getElementById('transaction_type').value;
    const amountLabel = document.getElementById('amountLabel');
    
    switch(transactionType) {
        case 'deposit':
            amountLabel.textContent = 'Deposit Amount';
            break;
        case 'withdraw':
            amountLabel.textContent = 'Withdrawal Amount';
            break;
        case 'transfer':
            amountLabel.textContent = 'Transfer Amount';
            break;
        case 'loan_payment':
            amountLabel.textContent = 'Payment Amount';
            break;
        default:
            amountLabel.textContent = 'Amount';
    }
    
    calculateNewBalance();
}

function calculateNewBalance() {
    const newAmount = parseFloat(document.getElementById('amount').value) || 0;
    const newType = document.getElementById('transaction_type').value;
    const balanceImpact = document.getElementById('balanceImpact');
    
    if (newAmount !== originalAmount || newType !== originalType) {
        // Calculate the adjustment needed
        let originalEffect = (originalType === 'deposit' || originalType === 'loan_payment') ? originalAmount : -originalAmount;
        let newEffect = (newType === 'deposit' || newType === 'loan_payment') ? newAmount : -newAmount;
        
        let adjustment = newEffect - originalEffect;
        let newBalance = currentBalance + adjustment;
        
        document.getElementById('balanceAdjustment').textContent = 
            (adjustment >= 0 ? '+' : '') + '$' + adjustment.toFixed(2);
        document.getElementById('newBalance').textContent = newBalance.toFixed(2);
        
        // Color code the adjustment
        const adjustmentElement = document.getElementById('balanceAdjustment');
        adjustmentElement.className = adjustment >= 0 ? 'text-success' : 'text-danger';
        
        balanceImpact.style.display = 'block';
    } else {
        balanceImpact.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateAmountLabel();
});
</script>
@endpush