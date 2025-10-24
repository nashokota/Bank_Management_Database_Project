@extends('layouts.app')

@section('title', 'Accounts - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-wallet me-2"></i>Account Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('accounts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Account
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Accounts</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('accounts.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="account_type" class="form-label">Account Type</label>
                    <select class="form-select" name="account_type" id="account_type">
                        <option value="">All Types</option>
                        <option value="savings" {{ request('account_type') == 'savings' ? 'selected' : '' }}>Savings</option>
                        <option value="current" {{ request('account_type') == 'current' ? 'selected' : '' }}>Current</option>
                        <option value="fixed_deposit" {{ request('account_type') == 'fixed_deposit' ? 'selected' : '' }}>Fixed Deposit</option>
                        <option value="loan" {{ request('account_type') == 'loan' ? 'selected' : '' }}>Loan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" id="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select class="form-select" name="branch_id" id="branch_id">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
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
                    <label for="min_balance" class="form-label">Min Balance</label>
                    <input type="number" class="form-control" name="min_balance" id="min_balance" 
                           value="{{ request('min_balance') }}" step="0.01">
                </div>
                <div class="col-md-3">
                    <label for="max_balance" class="form-label">Max Balance</label>
                    <input type="number" class="form-control" name="max_balance" id="max_balance" 
                           value="{{ request('max_balance') }}" step="0.01">
                </div>
                <div class="col-md-6">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Accounts Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Accounts List ({{ $accounts->total() }} total)</h5>
    </div>
    <div class="card-body">
        @if($accounts->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Branch</th>
                            <th>Account Type</th>
                            <th>Balance</th>
                            <th>Date Opened</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts as $account)
                        <tr>
                            <td><strong>#{{ $account->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $account->customer->name }}</div>
                                        <small class="text-muted">{{ $account->customer->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $account->branch->branch_name }}</div>
                                <small class="text-muted">{{ $account->branch->phone }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $account->account_type == 'savings' ? 'success' : 
                                    ($account->account_type == 'current' ? 'info' : 
                                    ($account->account_type == 'fixed_deposit' ? 'warning' : 'danger'))
                                }}">
                                    {{ ucwords(str_replace('_', ' ', $account->account_type)) }}
                                </span>
                            </td>
                            <td class="fw-bold text-{{ $account->balance >= 0 ? 'success' : 'danger' }}">
                                ${{ number_format((float)$account->balance, 2) }}
                            </td>
                            <td>{{ $account->date_opened->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $account->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($account->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('accounts.show', $account) }}" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('accounts.edit', $account) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Delete" 
                                            onclick="confirmDelete({{ $account->id }})">
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
                {{ $accounts->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-wallet fa-3x text-muted mb-3"></i>
                <h5>No Accounts Found</h5>
                <p class="text-muted">No accounts match your current filters.</p>
                <a href="{{ route('accounts.create') }}" class="btn btn-primary">Create First Account</a>
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
                Are you sure you want to delete this account? This action cannot be undone.
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

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.8rem;
}
</style>
@endsection

@push('scripts')
<script>
function confirmDelete(accountId) {
    const form = document.getElementById('deleteForm');
    form.action = `/accounts/${accountId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush