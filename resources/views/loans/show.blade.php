@extends('layouts.app')

@section('title', 'Loan Details - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-hand-holding-usd me-2"></i>Loan Details #{{ $loan->id }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('loans.edit', $loan) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Edit Loan
            </a>
            <a href="{{ route('loans.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Loans
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Loan Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Loan ID:</strong></td>
                                <td>#{{ $loan->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Loan Type:</strong></td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $loan->loan_type == 'home' ? 'success' : 
                                        ($loan->loan_type == 'car' ? 'primary' : 
                                        ($loan->loan_type == 'education' ? 'info' : 'secondary'))
                                    }}">
                                        {{ ucfirst($loan->loan_type) }} Loan
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Loan Amount:</strong></td>
                                <td><span class="h5 text-primary">${{ number_format($loan->loan_amount, 2) }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Interest Rate:</strong></td>
                                <td><span class="h6">{{ number_format($loan->interest_rate, 2) }}%</span></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $loan->status == 'ongoing' ? 'success' : 
                                        ($loan->status == 'closed' ? 'secondary' : 'danger')
                                    }}">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Start Date:</strong></td>
                                <td>
                                    @if($loan->start_date)
                                        {{ $loan->start_date->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>End Date:</strong></td>
                                <td>
                                    @if($loan->end_date)
                                        {{ $loan->end_date->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Duration:</strong></td>
                                <td>
                                    @if($loan->start_date && $loan->end_date)
                                        {{ $loan->start_date->diffInDays($loan->end_date) }} days
                                    @else
                                        <span class="text-muted">Cannot calculate</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $loan->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $loan->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Customer Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-lg bg-primary rounded-circle d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-user fa-2x text-white"></i>
                    </div>
                </div>
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $loan->customer->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $loan->customer->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>{{ $loan->customer->phone }}</td>
                    </tr>
                    <tr>
                        <td><strong>DOB:</strong></td>
                        <td>
                            @if($loan->customer->dob)
                                {{ $loan->customer->dob->format('M d, Y') }}
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="text-center">
                    <a href="{{ route('customers.show', $loan->customer) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>View Customer
                    </a>
                </div>
            </div>
        </div>

        @php
            if ($loan->start_date && $loan->end_date) {
                $yearsDiff = $loan->start_date->diffInYears($loan->end_date, false);
                $monthsDiff = $loan->start_date->diffInMonths($loan->end_date);
                $totalInterest = $loan->loan_amount * ($loan->interest_rate / 100) * ($yearsDiff ?: 1);
                $totalAmount = $loan->loan_amount + $totalInterest;
                $monthlyPayment = $totalAmount / max(1, $monthsDiff ?: 1);
            } else {
                $totalInterest = 0;
                $totalAmount = $loan->loan_amount;
                $monthlyPayment = 0;
            }
        @endphp

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Loan Calculations</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Principal Amount:</strong></td>
                        <td class="text-end">${{ number_format($loan->loan_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Interest:</strong></td>
                        <td class="text-end text-warning">${{ number_format($totalInterest, 2) }}</td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>Total Amount:</strong></td>
                        <td class="text-end"><strong>${{ number_format($totalAmount, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Monthly Payment:</strong></td>
                        <td class="text-end text-primary"><strong>${{ number_format($monthlyPayment, 2) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Actions</h5>
    </div>
    <div class="card-body">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('loans.edit', $loan) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Edit Loan
            </a>
            <form action="{{ route('loans.destroy', $loan) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" 
                        onclick="return confirm('Are you sure you want to delete this loan? This action cannot be undone.')">
                    <i class="fas fa-trash me-1"></i>Delete Loan
                </button>
            </form>
        </div>
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

<style>
.avatar-lg {
    width: 4rem;
    height: 4rem;
}
</style>
@endsection