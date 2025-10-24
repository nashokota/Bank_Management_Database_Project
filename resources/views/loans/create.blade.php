@extends('layouts.app')

@section('title', 'Add New Loan - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus me-2"></i>Add New Loan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('loans.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Loans
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-handshake me-2"></i>Loan Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('loans.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
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
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="loan_type" class="form-label">Loan Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('loan_type') is-invalid @enderror" 
                                        name="loan_type" id="loan_type" required>
                                    <option value="">Select Loan Type</option>
                                    <option value="home" {{ old('loan_type') == 'home' ? 'selected' : '' }}>Home Loan</option>
                                    <option value="car" {{ old('loan_type') == 'car' ? 'selected' : '' }}>Car Loan</option>
                                    <option value="education" {{ old('loan_type') == 'education' ? 'selected' : '' }}>Education Loan</option>
                                    <option value="personal" {{ old('loan_type') == 'personal' ? 'selected' : '' }}>Personal Loan</option>
                                </select>
                                @error('loan_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="loan_amount" class="form-label">Loan Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('loan_amount') is-invalid @enderror" 
                                           name="loan_amount" id="loan_amount" value="{{ old('loan_amount') }}" 
                                           placeholder="0.00" step="0.01" min="0" required>
                                </div>
                                @error('loan_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="interest_rate" class="form-label">Interest Rate (%) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('interest_rate') is-invalid @enderror" 
                                           name="interest_rate" id="interest_rate" value="{{ old('interest_rate') }}" 
                                           placeholder="0.00" step="0.01" min="0" max="100" required>
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('interest_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       name="start_date" id="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       name="end_date" id="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                            <option value="ongoing" {{ old('status', 'ongoing') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="defaulted" {{ old('status') == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('loans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Create Loan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('start_date').setAttribute('min', today);
    
    // Update end date minimum when start date changes
    document.getElementById('start_date').addEventListener('change', function() {
        document.getElementById('end_date').setAttribute('min', this.value);
    });
});
</script>
@endsection