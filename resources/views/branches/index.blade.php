@extends('layouts.app')

@section('title', 'Branches - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-building me-2"></i>Branch Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('branches.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Branch
        </a>
    </div>
</div>

<!-- Search -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search Branches</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('branches.index') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Branch Name</label>
                    <input type="text" class="form-control" name="search" id="search" 
                           value="{{ request('search') }}" placeholder="Enter branch name">
                </div>
                <div class="col-md-6">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="{{ route('branches.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Branches Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Branches List ({{ $branches->total() }} total)</h5>
    </div>
    <div class="card-body">
        @if($branches->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Branch Name</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Accounts</th>
                            <th>Employees</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($branches as $branch)
                        <tr>
                            <td><strong>#{{ $branch->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-building text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $branch->branch_name }}</div>
                                        <small class="text-muted">Branch {{ $branch->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $branch->phone }}</div>
                                <small class="text-muted">Phone</small>
                            </td>
                            <td>
                                <div class="text-wrap" style="max-width: 200px;">
                                    <small>{{ $branch->address }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $branch->accounts_count ?? 0 }} accounts</span>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $branch->employees_count ?? 0 }} employees</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('branches.show', $branch) }}" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('branches.edit', $branch) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Delete" 
                                            onclick="confirmDelete({{ $branch->id }})">
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
                {{ $branches->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h5>No Branches Found</h5>
                <p class="text-muted">No branches match your search criteria.</p>
                <a href="{{ route('branches.create') }}" class="btn btn-primary">Add First Branch</a>
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
                Are you sure you want to delete this branch? This will also delete all associated accounts, transactions, and employees.
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
function confirmDelete(branchId) {
    const form = document.getElementById('deleteForm');
    form.action = `/branches/${branchId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush