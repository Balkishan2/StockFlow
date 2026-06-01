@extends('layout.app')
@section('title', 'Sales Report')
@section('content')

<div class="container-fluid py-4">
    <!-- Page Header Row -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="h3 mb-0 text-dark fw-bold">Sales Report</h3>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sales Report</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-4">
            <form method="GET" action="{{ route('reports.sales') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-6 d-flex justify-content-md-end justify-content-start gap-2">
                    <button type="submit" class="btn btn-dark px-4">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('reports.sales') }}" class="btn btn-outline-secondary px-3">
                        <i class="fas fa-sync-alt me-1"></i>Reset
                    </a>
                    <a href="{{ route('reports.sales.export', request()->query()) }}" class="btn btn-success px-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                        <i class="fas fa-file-csv me-2"></i>Export CSV
                    </a>
                </div>
            </form>
        </div>
    </div>




    <!-- Summary Metrics Cards -->
    <div class="row row-cols-1 row-cols-md-5 g-3 mb-4">
        <!-- Revenue Card -->
        <div class="col">
            <div class="card reports-card border-left-indigo shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2 text-truncate">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1 text-truncate" style="font-size: 11px; letter-spacing: 0.5px;">
                                Total Revenue
                            </div>
                            <div class="fw-bold text-gray-800 text-truncate" style="font-size: 17px; color: #1e293b;" title="Rs {{ number_format($totalRevenue, 2) }}">
                                Rs {{ number_format($totalRevenue, 2) }}
                            </div>
                        </div>
                        <div class="card-icon icon-indigo">
                            <i class="fas fa-indian-rupee-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices Card -->
        <div class="col">
            <div class="card reports-card border-left-blue shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2 text-truncate">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1 text-truncate" style="font-size: 11px; letter-spacing: 0.5px;">
                                Total Invoices
                            </div>
                            <div class="fw-bold text-gray-800 text-truncate" style="font-size: 17px; color: #1e293b;">
                                {{ number_format($totalInvoices) }}
                            </div>
                        </div>
                        <div class="card-icon icon-blue">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Sold Card -->
        <div class="col">
            <div class="card reports-card border-left-green shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2 text-truncate">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1 text-truncate" style="font-size: 11px; letter-spacing: 0.5px;">
                                Items Sold
                            </div>
                            <div class="fw-bold text-gray-800 text-truncate" style="font-size: 17px; color: #1e293b;">
                                {{ number_format($totalItemsSold) }}
                            </div>
                        </div>
                        <div class="card-icon icon-green">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax Card -->
        <div class="col">
            <div class="card reports-card border-left-amber shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2 text-truncate">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1 text-truncate" style="font-size: 11px; letter-spacing: 0.5px;">
                                Total Tax
                            </div>
                            <div class="fw-bold text-gray-800 text-truncate" style="font-size: 17px; color: #1e293b;" title="Rs {{ number_format($totalTax, 2) }}">
                                Rs {{ number_format($totalTax, 2) }}
                            </div>
                        </div>
                        <div class="card-icon icon-amber">
                            <i class="fas fa-percent"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Discount Card -->
        <div class="col">
            <div class="card reports-card border-left-red shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2 text-truncate">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1 text-truncate" style="font-size: 11px; letter-spacing: 0.5px;">
                                Total Discount
                            </div>
                            <div class="fw-bold text-gray-800 text-truncate" style="font-size: 17px; color: #1e293b;" title="Rs {{ number_format($totalDiscount, 2) }}">
                                Rs {{ number_format($totalDiscount, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="card-icon icon-red">
                                <i class="fas fa-tags"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
            <h5 class="fw-bold text-dark mb-0">Sales Transactions</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Invoice ID</th>
                            <th>Invoice Date</th>
                            <th>Customer Name</th>
                            <th>Subtotal</th>
                            <th>Tax</th>
                            <th>Discount</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-medium text-dark">#{{ $invoice->id }}</span>
                            </td>
                            <td>{{ $invoice->invoice_date->format('d M, Y') }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $invoice->customer->name }}</div>
                                <div class="text-muted small" style="font-size: 11px;">{{ $invoice->customer->email ?? '-' }}</div>
                            </td>
                            <td>Rs {{ number_format($invoice->subtotal, 2) }}</td>
                            <td class="text-warning">Rs {{ number_format($invoice->total_tax, 2) }}</td>
                            <td class="text-danger">Rs {{ number_format($invoice->total_discount, 2) }}</td>
                            <td class="fw-bold text-success">Rs {{ number_format($invoice->total_amount, 2) }}</td>
                            <td>
                                @if($invoice->status === 'paid')
                                    <span class="badge bg-success-subtle text-success px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">PAID</span>
                                @elseif($invoice->status === 'unpaid')
                                    <span class="badge bg-warning-subtle text-warning px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">UNPAID</span>
                                @elseif($invoice->status === 'overdue')
                                    <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">OVERDUE</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">{{ strtoupper($invoice->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-file-invoice-dollar fa-3x mb-3 text-light"></i><br>
                                <h5 class="fw-normal text-secondary mb-1">No sales found in this period</h5>
                                <p class="small">Try expanding your date filter or finalise some draft invoices.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if($invoices->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-end">
            {{ $invoices->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
