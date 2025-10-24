@extends('layouts.app')

@section('title', 'Create Account - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus-circle me-2"></i>Create New Account</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
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
                <form method="POST" action="{{ route('accounts.store') }}">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Customer *</label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" 
                                    name="customer_id" id="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} - {{ $customer->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <a href="{{ route('customers.create') }}" target="_blank" class="text-decoration-none">
                                    <i class="fas fa-plus me-1"></i>Add New Customer
                                </a>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="branch_id" class="form-label">Branch *</label>
                            <select class="form-select @error('branch_id') is-invalid @enderror" 
                                    name="branch_id" id="branch_id" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <a href="{{ route('branches.create') }}" target="_blank" class="text-decoration-none">
                                    <i class="fas fa-plus me-1"></i>Add New Branch
                                </a>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="account_type" class="form-label">Account Type *</label>
                            <select class="form-select @error('account_type') is-invalid @enderror" 
                                    name="account_type" id="account_type" required>
                                <option value="">Select Account Type</option>
                                <option value="savings" {{ old('account_type') == 'savings' ? 'selected' : '' }}>Savings Account</option>
                                <option value="current" {{ old('account_type') == 'current' ? 'selected' : '' }}>Current Account</option>
                                <option value="fixed_deposit" {{ old('account_type') == 'fixed_deposit' ? 'selected' : '' }}>Fixed Deposit</option>
                                <option value="loan" {{ old('account_type') == 'loan' ? 'selected' : '' }}>Loan Account</option>
                            </select>
                            @error('account_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="balance" class="form-label">Initial Balance *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('balance') is-invalid @enderror" 
                                       name="balance" id="balance" value="{{ old('balance', '0.00') }}" 
                                       min="0" step="0.01" required>
                            </div>
                            @error('balance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimum opening balance varies by account type</div>
                        </div>

                        <div class="col-md-6">
                            <label for="date_opened" class="form-label">Date Opened *</label>
                            <input type="date" class="form-control @error('date_opened') is-invalid @enderror" 
                                   name="date_opened" id="date_opened" value="{{ old('date_opened', date('Y-m-d')) }}" required>
                            @error('date_opened')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    name="status" id="status" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Create Account
                                </button>
                                <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Type Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Account Type Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success">Savings Account</h6>
                        <ul class="list-unstyled text-sm">
                            <li><i class="fas fa-check text-success me-1"></i>Earn interest on deposits</li>
                            <li><i class="fas fa-check text-success me-1"></i>Minimum balance: $100</li>
                            <li><i class="fas fa-check text-success me-1"></i>Limited monthly transactions</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info">Current Account</h6>
                        <ul class="list-unstyled text-sm">
                            <li><i class="fas fa-check text-success me-1"></i>No interest earned</li>
                            <li><i class="fas fa-check text-success me-1"></i>Minimum balance: $500</li>
                            <li><i class="fas fa-check text-success me-1"></i>Unlimited transactions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const accountTypeSelect = document.getElementById('account_type');
    const balanceInput = document.getElementById('balance');
    
    accountTypeSelect.addEventListener('change', function() {
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
        if (parseFloat(balanceInput.value) < minBalance) {
            balanceInput.value = minBalance.toFixed(2);
        }
    });
});
</script>
@endpush