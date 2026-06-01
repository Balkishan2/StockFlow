@extends('layout.app')
@section('title', 'Add User')
@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="h3 mb-0 text-dark fw-bold">Add User</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb" class="me-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users') }}" class="text-decoration-none">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add User</li>
                </ol>
            </nav>
            <a href="{{ route('users') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-times fa-sm me-1"></i> Cancel
            </a>
        </div>
    </div>

    <!-- Form Row -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white pt-4 pb-2 border-bottom-0">
                    <h5 class="fw-bold text-dark mb-0">User Account Details</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('users.add') }}">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name') }}" required placeholder="e.g. John Doe">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}" required placeholder="john.doe@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Mobile Number <span class="text-danger">*</span></label>
                                <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" 
                                    value="{{ old('mobile') }}" required placeholder="e.g. 9876543210">
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                    required placeholder="Enter secure password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-save me-1"></i> Save User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
