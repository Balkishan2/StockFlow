@extends('layout.app')
@section('title', 'View Order')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="h3 mb-0 text-dark fw-bold">Order Details</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb" class="me-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('orders') }}" class="text-decoration-none">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</li>
                </ol>
            </nav>
            <a href="{{ route('orders') }}" class="btn btn-light shadow-sm me-2">Back</a>
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-secondary shadow-sm me-2">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-primary shadow-sm" target="_blank">
                <i class="fas fa-print me-1"></i> Print Invoice
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Details -->
        <div class="col-lg-8">
            <!-- Customer Info -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Name</label>
                            <p class="mb-0 fw-medium text-dark">{{ $order->customer->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Email</label>
                            <p class="mb-0 text-dark">{{ $order->customer->email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Phone</label>
                            <p class="mb-0 text-dark">{{ $order->customer->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small fw-bold text-uppercase">Address</label>
                            <p class="mb-0 text-dark">{{ $order->customer->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items List -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0">Order Items</h5>
                </div>
                <div class="card-body p-0 mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="ps-4 fw-medium text-dark">{{ $item->item->name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rs {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end pe-4 fw-bold text-dark">Rs {{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 summary-card">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4">Order Summary</h5>
                    
                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase">Order Date</label>
                        <p class="mb-0 fw-medium text-dark">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase mb-2 d-block">Status</label>
                        @if($order->status === 'completed')
                            <span class="badge bg-success py-2 px-3">Completed</span>
                        @elseif($order->status === 'processing')
                            <span class="badge bg-info py-2 px-3">Processing</span>
                        @elseif($order->status === 'cancelled')
                            <span class="badge bg-danger py-2 px-3">Cancelled</span>
                        @else
                            <span class="badge bg-warning text-dark py-2 px-3">Pending</span>
                        @endif
                    </div>

                    <hr class="text-muted opacity-25 mb-4">

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Amount</span>
                        <span class="fw-bold text-dark fs-4">Rs {{ number_format($order->total_amount, 2) }}</span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.summary-card {
    background: #f8fafc;
    position: sticky;
    top: 20px;
}
</style>
@endsection
