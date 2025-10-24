@extends('layouts.app')

@section('title', 'Customers - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-users me-2"></i>Customer Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Customer
        </a>
    </div>
</div>

<!-- Search -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search Customers</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('customers.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" name="search" id="search" 
                           value="{{ request('search') }}" placeholder="Enter customer name">
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" 
                           value="{{ request('email') }}" placeholder="Enter email address">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Customers Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Customers List ({{ $customers->total() }} total)</h5>
    </div>
    <div class="card-body">
        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Date of Birth</th>
                            <th>Accounts</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td><strong>#{{ $customer->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $customer->name }}</div>
                                        <small class="text-muted">Customer since {{ $customer->created_at->format('Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $customer->email }}</div>
                                <small class="text-muted">{{ $customer->phone }}</small>
                            </td>
                            <td>
                                {{ $customer->dob->format('M d, Y') }}
                                <br><small class="text-muted">{{ $customer->dob->age }} years old</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $customer->accounts_count ?? 0 }} accounts</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('customers.show', $customer) }}" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Delete" 
                                            onclick="confirmDelete({{ $customer->id }})">
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
                {{ $customers->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5>No Customers Found</h5>
                <p class="text-muted">No customers match your search criteria.</p>
                <a href="{{ route('customers.create') }}" class="btn btn-primary">Add First Customer</a>
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
                Are you sure you want to delete this customer? This will also delete all associated accounts and transactions.
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
function confirmDelete(customerId) {
    const form = document.getElementById('deleteForm');
    form.action = `/customers/${customerId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush