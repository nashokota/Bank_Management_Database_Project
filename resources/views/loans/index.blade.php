@extends('layouts.app')

@section('title', 'Loans - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-hand-holding-usd me-2"></i>Loan Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('loans.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Loan
        </a>
    </div>
</div>

<!-- Search -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search Loans</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('loans.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="loan_type" class="form-label">Loan Type</label>
                    <select class="form-select" name="loan_type" id="loan_type">
                        <option value="">All Types</option>
                        <option value="home" {{ request('loan_type') == 'home' ? 'selected' : '' }}>Home Loan</option>
                        <option value="car" {{ request('loan_type') == 'car' ? 'selected' : '' }}>Car Loan</option>
                        <option value="education" {{ request('loan_type') == 'education' ? 'selected' : '' }}>Education Loan</option>
                        <option value="personal" {{ request('loan_type') == 'personal' ? 'selected' : '' }}>Personal Loan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" id="status">
                        <option value="">All Status</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="defaulted" {{ request('status') == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="customer_id" class="form-label">Customer</label>
                    <select class="form-select" name="customer_id" id="customer_id">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="{{ route('loans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label for="min_amount" class="form-label">Min Amount</label>
                    <input type="number" class="form-control" name="min_amount" id="min_amount" 
                           value="{{ request('min_amount') }}" placeholder="Minimum loan amount" step="0.01">
                </div>
                <div class="col-md-6">
                    <label for="max_amount" class="form-label">Max Amount</label>
                    <input type="number" class="form-control" name="max_amount" id="max_amount" 
                           value="{{ request('max_amount') }}" placeholder="Maximum loan amount" step="0.01">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Loans Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Loans List ({{ $loans->total() }} total)</h5>
    </div>
    <div class="card-body">
        @if($loans->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Loan Type</th>
                            <th>Amount</th>
                            <th>Interest Rate</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                        <tr>
                            <td><strong>#{{ $loan->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-warning rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $loan->customer->name }}</div>
                                        <small class="text-muted">{{ $loan->customer->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $loan->loan_type == 'home' ? 'success' : 
                                    ($loan->loan_type == 'car' ? 'primary' : 
                                    ($loan->loan_type == 'education' ? 'info' : 'secondary'))
                                }}">
                                    {{ ucfirst($loan->loan_type) }} Loan
                                </span>
                            </td>
                            <td>
                                <strong>${{ number_format($loan->loan_amount, 2) }}</strong>
                            </td>
                            <td>
                                {{ number_format($loan->interest_rate, 2) }}%
                            </td>
                            <td>
                                @if($loan->start_date)
                                    {{ $loan->start_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                                <br>
                                <small class="text-muted">to 
                                    @if($loan->end_date)
                                        {{ $loan->end_date->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $loan->status == 'ongoing' ? 'success' : 
                                    ($loan->status == 'closed' ? 'secondary' : 'danger')
                                }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('loans.show', $loan) }}" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('loans.edit', $loan) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('loans.destroy', $loan) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this loan?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $loans->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-hand-holding-usd fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No loans found</h5>
                <p class="text-muted">Try adjusting your search criteria or add a new loan.</p>
                <a href="{{ route('loans.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add First Loan
                </a>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast show" role="alert">
            <div class="toast-header">
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif
@endsection