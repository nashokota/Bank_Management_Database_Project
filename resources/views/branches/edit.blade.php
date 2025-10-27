@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Branch</h4>
                    <a href="{{ route('branches.show', $branch) }}" class="btn btn-secondary btn-sm">Cancel</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('branches.update', $branch) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="branch_name" class="form-label">Branch Name *</label>
                            <input type="text" 
                                   class="form-control @error('branch_name') is-invalid @enderror" 
                                   id="branch_name" 
                                   name="branch_name" 
                                   value="{{ old('branch_name', $branch->branch_name) }}" 
                                   required>
                            @error('branch_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      required>{{ old('address', $branch->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $branch->phone) }}" 
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Branch
                            </button>
                            
                            <div>
                                <a href="{{ route('branches.show', $branch) }}" class="btn btn-secondary me-2">Cancel</a>
                                
                                <!-- Delete Button -->
                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Hidden Delete Form -->
                    <form id="delete-form" action="{{ route('branches.destroy', $branch) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this branch? This action cannot be undone and will affect related accounts and employees.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection