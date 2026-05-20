@extends('layout.app')
@section('title', 'Customers')
@section('content')

<div class="container-fluid py-4">
    <!-- Page Header Row -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="h3 mb-0 text-dark fw-bold">Products</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb" class="me-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                </ol>
            </nav>
            <a href="{{ route('products.add') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Add Product
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
                    <form method="GET" action="{{ route('products.listing') }}" class="row g-3 align-items-center">
                        <div class="col-lg-4 col-md-12">
                            <label for="search" class="visually-hidden">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search by name, item code">
                            </div>
                        </div>
                        <div class="col-lg-auto col-md-4 d-flex align-items-center">
                            <button type="submit" class="btn btn-dark px-4 shadow-sm border-0">
                                Filter
                            </button>
                            <a href="{{ route('products.listing') }}" class="text-muted text-decoration-none ms-3" style="font-size: 14px; transition: color 0.2s;" onmouseover="this.className='text-danger text-decoration-none ms-3'" onmouseout="this.className='text-muted text-decoration-none ms-3'">
                                <i class="fas fa-times me-1"></i>Clear
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Table Section -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $product)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $product->id }}</td>
                                    <td class="fw-medium text-dark">{{ $product->name }}</td>
                                    <td>{{ $product->sku ?? '-' }}</td>
                                    <td>{{ $product->cost_price ? 'Rs ' . number_format($product->cost_price, 2) : '-' }}</td>
                                    <td class="text-truncate" style="max-width: 200px;" title="{{ $product->selling_price }}">
                                        {{ $product->selling_price ? 'Rs ' . number_format($product->selling_price, 2) : '-' }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-light text-primary me-1" title="Edit Product"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('products.delete', $product->id) }}" class="btn btn-sm btn-light text-danger" title="Delete Product" onclick="return confirm('Are you sure you want to delete this product?');"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3 text-light"></i><br>
                                        <h5 class="fw-normal text-secondary mb-1">No products found</h5>
                                        <p class="small">Try adjusting your filters or add a new product.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination -->
                @if($data->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-end">
                    {{ $data->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
