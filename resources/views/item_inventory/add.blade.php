@extends('layout.app')
@section('title', 'Add Inventory')
@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="h3 mb-0 text-dark fw-bold">Add Inventory</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb" class="me-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory') }}" class="text-decoration-none">Item Inventory</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Inventory</li>
                </ol>
            </nav>
            <a href="{{ route('inventory') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-times fa-sm me-1"></i> Cancel
            </a>
        </div>
    </div>

    <!-- Form Row -->
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white pt-4 pb-2 border-bottom-0">
                    <h5 class="fw-bold text-dark mb-0">Inventory Details</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('inventory.add') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">Select Item <span class="text-danger">*</span></label>
                            <select name="item_id" class="form-select @error('item_id') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Select an Item --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }} (SKU: {{ $item->sku ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($items->isEmpty())
                                <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle"></i> No items found. Please create a product first.</small>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Initial Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="current_stock" class="form-control @error('current_stock') is-invalid @enderror" 
                                value="{{ old('current_stock', 0) }}" min="0" required placeholder="E.g. 50">
                            @error('current_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" {{ $items->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-save me-1"></i> Save Inventory
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
