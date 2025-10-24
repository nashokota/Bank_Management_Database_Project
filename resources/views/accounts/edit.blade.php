@extends('layouts.app')

@section('title', 'Edit Account - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-edit me-2"></i>Edit Account</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('accounts.show', $account) }}" class="btn btn-info">
                <i class="fas fa-eye me-1"></i>View Account
            </a>
        </div>
        <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Accounts
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-wallet me-2"></i>Account Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('accounts.update', $account) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Account ID:</strong> #{{ $account->id }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Customer *</label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" 
                                    name="customer_id" id="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            {{ (old('customer_id', $account->customer_id) == $customer->id) ? 'selected' : '' }}>
                                        {{ $customer->name }} - {{ $customer->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="branch_id" class="form-label">Branch *</label>
                            <select class="form-select @error('branch_id') is-invalid @enderror" 
                                    name="branch_id" id="branch_id" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" 
                                            {{ (old('branch_id', $account->branch_id) == $branch->id) ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="account_type" class="form-label">Account Type *</label>
                            <select class="form-select @error('account_type') is-invalid @enderror" 
                                    name="account_type" id="account_type" required>
                                <option value="">Select Account Type</option>
                                <option value="savings" {{ (old('account_type', $account->account_type) == 'savings') ? 'selected' : '' }}>
                                    Savings Account
                                </option>
                                <option value="current" {{ (old('account_type', $account->account_type) == 'current') ? 'selected' : '' }}>
                                    Current Account
                                </option>
                                <option value="fixed_deposit" {{ (old('account_type', $account->account_type) == 'fixed_deposit') ? 'selected' : '' }}>
                                    Fixed Deposit
                                </option>
                                <option value="loan" {{ (old('account_type', $account->account_type) == 'loan') ? 'selected' : '' }}>
                                    Loan Account
                                </option>
                            </select>
                            @error('account_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="balance" class="form-label">Current Balance *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('balance') is-invalid @enderror" 
                                       name="balance" id="balance" 
                                       value="{{ old('balance', number_format((float)$account->balance, 2, '.', '')) }}" 
                                       min="0" step="0.01" required>
                            </div>
                            @error('balance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Changing the balance directly affects the account. Consider creating a transaction instead.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="date_opened" class="form-label">Date Opened *</label>
                            <input type="date" class="form-control @error('date_opened') is-invalid @enderror" 
                                   name="date_opened" id="date_opened" 
                                   value="{{ old('date_opened', $account->date_opened->format('Y-m-d')) }}" required>
                            @error('date_opened')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    name="status" id="status" required>
                                <option value="active" {{ (old('status', $account->status) == 'active') ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="closed" {{ (old('status', $account->status) == 'closed') ? 'selected' : '' }}>
                                    Closed
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Closing an account will restrict future transactions.
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update Account
                                </button>
                                <a href="{{ route('accounts.show', $account) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="button" class="btn btn-danger ms-auto" onclick="confirmDelete()">
                                    <i class="fas fa-trash me-1"></i>Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Activity Summary -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Account Activity Summary</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h5 class="text-primary">${{ number_format((float)$account->balance, 2) }}</h5>
                        <small class="text-muted">Current Balance</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-success">{{ $account->transactions->count() }}</h5>
                        <small class="text-muted">Total Transactions</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-info">{{ $account->date_opened->diffInDays(now()) }}</h5>
                        <small class="text-muted">Days Active</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-{{ $account->status == 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($account->status) }}
                        </h5>
                        <small class="text-muted">Account Status</small>
                    </div>
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
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i>Confirm Account Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This action cannot be undone!
                </div>
                <p>Are you sure you want to delete this account?</p>
                <ul class="text-muted">
                    <li>Account ID: #{{ $account->id }}</li>
                    <li>Customer: {{ $account->customer->name }}</li>
                    <li>Balance: ${{ number_format((float)$account->balance, 2) }}</li>
                    <li>Transactions: {{ $account->transactions->count() }} records</li>
                </ul>
                <p class="text-danger">
                    <strong>All transaction history associated with this account will also be deleted.</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('accounts.destroy', $account) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Account
                    </button>
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

// Auto-update balance validation based on account type
document.getElementById('account_type').addEventListener('change', function() {
    const balanceInput = document.getElementById('balance');
    const accountType = this.value;
    let minBalance = 0;
    
    switch(accountType) {
        case 'savings':
            minBalance = 100;
            break;
        case 'current':
            minBalance = 500;
            break;
        case 'fixed_deposit':
            minBalance = 1000;
            break;
        case 'loan':
            minBalance = 0;
            break;
    }
    
    balanceInput.min = minBalance;
    
    // Show warning if current balance is below minimum
    if (parseFloat(balanceInput.value) < minBalance) {
        balanceInput.classList.add('is-invalid');
        const feedback = balanceInput.parentNode.querySelector('.invalid-feedback') || 
                        document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = `Minimum balance for ${accountType} account is $${minBalance}`;
        balanceInput.parentNode.appendChild(feedback);
    } else {
        balanceInput.classList.remove('is-invalid');
    }
});
</script>
@endpush