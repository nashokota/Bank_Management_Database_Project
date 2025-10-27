@extends('layouts.app')

@section('title', 'New Transaction - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus-circle me-2"></i>New Transaction</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Transactions
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Transaction Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <!-- Account Selection -->
                        <div class="col-md-6">
                            <label for="account_id" class="form-label">Account <span class="text-danger">*</span></label>
                            <select class="form-select @error('account_id') is-invalid @enderror" 
                                    name="account_id" id="account_id" required onchange="loadAccountDetails()">
                                <option value="">Select Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" 
                                            data-balance="{{ $account->balance }}"
                                            data-customer="{{ $account->customer->name }}"
                                            data-branch="{{ $account->branch->branch_name }}"
                                            {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                        #{{ $account->id }} - {{ $account->customer->name }} (Balance: ${{ number_format((float)$account->balance, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('account_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Transaction Type -->
                        <div class="col-md-6">
                            <label for="transaction_type" class="form-label">Transaction Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('transaction_type') is-invalid @enderror" 
                                    name="transaction_type" id="transaction_type" required onchange="updateTransactionType()">
                                <option value="">Select Type</option>
                                <option value="deposit" {{ old('transaction_type') == 'deposit' ? 'selected' : '' }}>
                                    Deposit
                                </option>
                                <option value="withdraw" {{ old('transaction_type') == 'withdraw' ? 'selected' : '' }}>
                                    Withdrawal
                                </option>
                                <option value="transfer" {{ old('transaction_type') == 'transfer' ? 'selected' : '' }}>
                                    Transfer
                                </option>
                                <option value="loan_payment" {{ old('transaction_type') == 'loan_payment' ? 'selected' : '' }}>
                                    Loan Payment
                                </option>
                            </select>
                            @error('transaction_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Destination Account (for transfers only) -->
                        <div class="col-md-6" id="destinationAccountDiv" style="display: none;">
                            <label for="destination_account_id" class="form-label">Destination Account <span class="text-danger">*</span></label>
                            <select class="form-select @error('destination_account_id') is-invalid @enderror" 
                                    name="destination_account_id" id="destination_account_id">
                                <option value="">Select Destination Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" 
                                            data-balance="{{ $account->balance }}"
                                            data-customer="{{ $account->customer->name }}"
                                            {{ old('destination_account_id') == $account->id ? 'selected' : '' }}>
                                        #{{ $account->id }} - {{ $account->customer->name }} (Balance: ${{ number_format((float)$account->balance, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('destination_account_id')
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
                                       value="{{ old('amount') }}" 
                                       step="0.01" 
                                       min="0.01"
                                       required
                                       oninput="calculateBalance()">
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date & Time -->
                        <div class="col-md-6">
                            <label for="date_time" class="form-label">Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" 
                                   class="form-control @error('date_time') is-invalid @enderror" 
                                   name="date_time" 
                                   id="date_time" 
                                   value="{{ old('date_time', now()->format('Y-m-d\TH:i')) }}" 
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
                                      placeholder="Optional transaction description">{{ old('description') }}</textarea>
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
                                    <i class="fas fa-save me-1"></i>Process Transaction
                                </button>
                                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
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
        <div class="card" id="accountSummary" style="display: none;">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Account Summary</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Customer</label>
                    <div class="fw-bold" id="customerName"></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Branch</label>
                    <div id="branchName"></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Current Balance</label>
                    <div class="h5 text-primary" id="currentBalance"></div>
                </div>
                <div id="newBalanceSection" style="display: none;">
                    <label class="text-muted small">Balance After Transaction</label>
                    <div class="h5" id="newBalance"></div>
                </div>
            </div>
        </div>

        <!-- Transaction Guidelines -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Guidelines</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li><i class="fas fa-check-circle text-success me-2"></i>Deposits add money to account</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i>Withdrawals remove money from account</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i>Transfers can be between accounts</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i>Loan payments reduce loan balance</li>
                    <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Ensure sufficient balance for withdrawals</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentBalance = 0;

function loadAccountDetails() {
    const select = document.getElementById('account_id');
    const selectedOption = select.options[select.selectedIndex];
    const summaryCard = document.getElementById('accountSummary');
    
    if (selectedOption.value) {
        currentBalance = parseFloat(selectedOption.dataset.balance);
        
        document.getElementById('customerName').textContent = selectedOption.dataset.customer;
        document.getElementById('branchName').textContent = selectedOption.dataset.branch;
        document.getElementById('currentBalance').textContent = '$' + new Intl.NumberFormat().format(currentBalance);
        
        summaryCard.style.display = 'block';
        calculateBalance();
    } else {
        summaryCard.style.display = 'none';
        currentBalance = 0;
    }
}

function updateTransactionType() {
    updateAmountLabel();
    toggleDestinationAccount();
    calculateBalance();
}

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
}

function toggleDestinationAccount() {
    const transactionType = document.getElementById('transaction_type').value;
    const destinationDiv = document.getElementById('destinationAccountDiv');
    const destinationSelect = document.getElementById('destination_account_id');
    const sourceAccountId = document.getElementById('account_id').value;
    
    if (transactionType === 'transfer') {
        destinationDiv.style.display = 'block';
        destinationSelect.required = true;
        
        // Filter out the source account from destination options
        Array.from(destinationSelect.options).forEach(option => {
            if (option.value === sourceAccountId) {
                option.style.display = 'none';
                option.disabled = true;
            } else {
                option.style.display = 'block';
                option.disabled = false;
            }
        });
    } else {
        destinationDiv.style.display = 'none';
        destinationSelect.required = false;
        destinationSelect.value = '';
    }
}

function calculateBalance() {
    const amountInput = document.getElementById('amount');
    const transactionType = document.getElementById('transaction_type').value;
    const newBalanceSection = document.getElementById('newBalanceSection');
    const newBalanceElement = document.getElementById('newBalance');
    
    if (currentBalance > 0 && amountInput.value && transactionType) {
        const amount = parseFloat(amountInput.value);
        let newBalance = currentBalance;
        
        if (transactionType === 'deposit' || transactionType === 'loan_payment') {
            newBalance = currentBalance + amount;
        } else if (transactionType === 'withdraw' || transactionType === 'transfer') {
            newBalance = currentBalance - amount;
        }
        
        newBalanceElement.textContent = '$' + new Intl.NumberFormat().format(newBalance);
        newBalanceElement.className = 'h5 ' + (newBalance >= 0 ? 'text-success' : 'text-danger');
        newBalanceSection.style.display = 'block';
        
        // Add warning for insufficient funds
        if (newBalance < 0) {
            if (!document.getElementById('insufficientFundsWarning')) {
                const warning = document.createElement('div');
                warning.id = 'insufficientFundsWarning';
                warning.className = 'alert alert-warning mt-2';
                warning.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Insufficient funds for this transaction!';
                newBalanceSection.appendChild(warning);
            }
        } else {
            const existingWarning = document.getElementById('insufficientFundsWarning');
            if (existingWarning) {
                existingWarning.remove();
            }
        }
    } else {
        newBalanceSection.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAccountDetails();
    updateTransactionType();
    
    // Add event listener to source account to update destination options
    document.getElementById('account_id').addEventListener('change', function() {
        loadAccountDetails();
        toggleDestinationAccount();
    });
});
</script>
@endpush