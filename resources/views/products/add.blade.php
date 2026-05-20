@extends('layout.app')
@section('title', 'Add Product')
@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="h3 mb-0 text-dark fw-bold">Add Product</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb" class="me-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.listing') }}" class="text-decoration-none">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Product</li>
                </ol>
            </nav>
            <a href="{{ route('products.listing') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-times fa-sm me-1"></i> Cancel
            </a>
        </div>
    </div>

    <!-- Form Row -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white pt-4 pb-2 border-bottom-0">
                    <h5 class="fw-bold text-dark mb-0">Product Information</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('products.add') }}">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name') }}" required placeholder="e.g. Wireless Mouse">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">SKU / Item Code <span class="text-danger">*</span></label>
                                <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                                    value="{{ old('sku') }}" required placeholder="e.g. SKU-WM001">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Cost Price (Rs) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" 
                                    value="{{ old('cost_price') }}" required placeholder="0.00">
                                @error('cost_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Selling Price (Rs) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror" 
                                    value="{{ old('selling_price') }}" required placeholder="0.00">
                                @error('selling_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-medium text-secondary">Description</label>
                                <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" 
                                    placeholder="Enter product description...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-save me-1"></i> Save Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
