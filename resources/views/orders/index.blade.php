@extends('layout.app')
@section('title', 'Orders')
@section('content')

<div class="container-fluid py-4">
    <!-- Page Header Row -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="h3 mb-0 text-dark fw-bold">Orders</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb" class="me-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">Orders</li>
                </ol>
            </nav>
            <a href="/orders/add" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Add Order
            </a>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
    <div class="card-header border-bottom-0 pt-4 pb-3">
                    <form method="GET" action="{{ route('orders') }}" class="row g-3 align-items-center">
                        <div class="col-lg-4 col-md-12">
                            <label for="search" class="visually-hidden">Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="search" name="search" value="{{ request('search') }}" placeholder="Search by Order ID or Customer">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <select class="form-select" name="status">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <input type="date" class="form-control" name="date" value="{{ request('date') }}" placeholder="Order Date">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
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
                                    <th class="ps-4">Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4 fw-medium">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</td>
                                    <td>
                                        @if($order->status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($order->status === 'processing')
                                            <span class="badge bg-info">Processing</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>Rs {{ number_format($order->total_amount, 2) }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('orders.view', $order->id) }}" class="btn btn-sm btn-light text-primary me-1" title="View Order"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-light text-secondary" title="Edit Order"><i class="fas fa-edit"></i></a>
                                    </td>
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
                    {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection