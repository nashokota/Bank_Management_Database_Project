@extends('layouts.app')

@section('title', 'Add Branch - Banking Management System')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-plus-circle me-2"></i>Add New Branch</h1>
    <a href="{{ route('branches.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to Branches
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-building me-2"></i>Branch Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('branches.store') }}">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="branch_name" class="form-label">Branch Name *</label>
                            <input type="text" class="form-control @error('branch_name') is-invalid @enderror" 
                                   name="branch_name" id="branch_name" value="{{ old('branch_name') }}" required>
                            @error('branch_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   name="phone" id="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Branch Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      name="address" id="address" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Create Branch
                        </button>
                        <a href="{{ route('branches.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection