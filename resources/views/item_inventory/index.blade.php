@extends('layout.app')
@section('title', 'Orders')
@section('content')

<div class="container-fluid py-4">
    <!-- Page Header Row -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="h3 mb-0 text-dark fw-bold">Item Inventory</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb" class="me-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">Item Inventory</li>
                </ol>
            </nav>
            <a href="/item-inventory/add" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Add Inventory
            </a>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
    <div class="card-header border-bottom-0 pt-4 pb-3">
                    <form method="GET" action="{{ route('inventory') }}" class="row g-3 align-items-center">
                        <div class="col-lg-4 col-md-12">
                            <label for="search" class="visually-hidden">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search by item name">
                            </div>
                        </div>
                        <div class="col-lg-auto col-md-4 d-flex align-items-center">
                            <button type="submit" class="btn btn-dark px-4 shadow-sm border-0">
                                Filter
                            </button>
                            <a href="{{ route('inventory') }}" class="text-muted text-decoration-none ms-3" style="font-size: 14px; transition: color 0.2s;" onmouseover="this.className='text-danger text-decoration-none ms-3'" onmouseout="this.className='text-muted text-decoration-none ms-3'">
                                <i class="fas fa-times me-1"></i>Clear
                            </a>
                        </div>
                    </form>
                </div>
    </div>
    <div class="row">
        <div class="col-12">
            <!-- Unified Card for Filter & Table -->
            <div class="card shadow-sm border-0 mb-4">
                
                <div class="card-body p-0">
                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Item Name</th>
                                    <th>Current Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $value)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $value->name }}</td>
                                    <td>{{ $value->current_stock }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-box-open fa-2x mb-2 text-light"></i><br>
                                        No orders found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="card-footer bg-white border-top-0 py-3 d-flex justify-content-end">
                    {{ $data->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection