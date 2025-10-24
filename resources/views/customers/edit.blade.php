@extends('layouts.app')

@section('title', 'Edit Customer - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-edit me-2"></i>Edit Customer</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('customers.show', $customer) }}" class="btn btn-info me-2">
            <i class="fas fa-eye me-1"></i>View Customer
        </a>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Customers
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Customer Information</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Customer ID:</strong> #{{ $customer->id }} | 
                    <strong>Member Since:</strong> {{ $customer->created_at->format('M d, Y') }}
                </div>

                <form method="POST" action="{{ route('customers.update', $customer) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" id="name" value="{{ old('name', $customer->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" id="email" value="{{ old('email', $customer->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="dob" class="form-label">Date of Birth *</label>
                            <input type="date" class="form-control @error('dob') is-invalid @enderror" 
                                   name="dob" id="dob" value="{{ old('dob', $customer->dob->format('Y-m-d')) }}" required>
                            @error('dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Current age: {{ $customer->dob->age }} years old</div>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   name="phone" id="phone" value="{{ old('phone', $customer->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      name="address" id="address" rows="3" required>{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Customer
                        </button>
                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="button" class="btn btn-danger ms-auto" onclick="confirmDelete()">
                            <i class="fas fa-trash me-1"></i>Delete Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Customer Summary -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Customer Summary</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h5 class="text-primary">{{ $customer->accounts->count() }}</h5>
                        <small class="text-muted">Total Accounts</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-success">${{ number_format($customer->accounts->sum('balance'), 2) }}</h5>
                        <small class="text-muted">Total Balance</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-warning">{{ $customer->loans->count() }}</h5>
                        <small class="text-muted">Total Loans</small>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-info">{{ $customer->accounts->where('status', 'active')->count() }}</h5>
                        <small class="text-muted">Active Accounts</small>
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
                    <i class="fas fa-exclamation-triangle me-1"></i>Confirm Customer Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This action cannot be undone!
                </div>
                <p>Are you sure you want to delete this customer?</p>
                <ul class="text-muted">
                    <li>Customer: {{ $customer->name }}</li>
                    <li>Email: {{ $customer->email }}</li>
                    <li>Accounts: {{ $customer->accounts->count() }} accounts</li>
                    <li>Total Balance: ${{ number_format($customer->accounts->sum('balance'), 2) }}</li>
                    <li>Loans: {{ $customer->loans->count() }} loans</li>
                </ul>
                <p class="text-danger">
                    <strong>All accounts, transactions, and loans associated with this customer will also be deleted.</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('customers.destroy', $customer) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Customer
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
</script>
@endpush