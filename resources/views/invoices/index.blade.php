@extends('layout.app')
@section('title', 'Sales Invoices')
@section('content')

<div class="container-fluid py-4">
    <!-- Page Header Row -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="h3 mb-0 text-dark fw-bold">Sales Invoices</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb" class="me-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">Sales Invoices</li>
                </ol>
            </nav>
            <a href="{{ route('invoices.add') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Add Invoice
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
                    <form method="GET" action="{{ route('invoices') }}" class="row g-3 align-items-center">
                        <div class="col-lg-4 col-md-12">
                            <label for="search" class="visually-hidden">Search</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="search" name="search" value="{{ request('search') }}" placeholder="Search by Invoice ID or Customer">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <select class="form-select shadow-sm" name="status">
                                <option value="">All Statuses</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <input type="date" class="form-control shadow-sm" name="date" value="{{ request('date') }}" title="Invoice Date">
                        </div>
                        <div class="col-lg-auto col-md-4 d-flex align-items-center">
                            <button type="submit" class="btn btn-dark px-4 shadow-sm border-0">
                                Filter
                            </button>
                            <a href="{{ route('invoices') }}" class="text-muted text-decoration-none ms-3" style="font-size: 14px; transition: color 0.2s;" onmouseover="this.className='text-danger text-decoration-none ms-3'" onmouseout="this.className='text-muted text-decoration-none ms-3'">
                                <i class="fas fa-times me-1"></i>Clear
                            </a>
                        </div>
                    </form>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Invoice #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Total Amount</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td class="ps-4 fw-medium">#INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-secondary fa-sm"></i>
                                            </div>
                                            <span>{{ $invoice->customer->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</td>
                                    <td>{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') : '-' }}</td>
                                    <td>
                                        @if($invoice->status === 'paid')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">Paid</span>
                                        @elseif($invoice->status === 'overdue')
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill">Overdue</span>
                                        @elseif($invoice->status === 'draft')
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill">Draft</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 rounded-pill">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">Rs {{ number_format($invoice->total_amount, 2) }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('invoices.print', $invoice->id) }}" class="btn btn-sm btn-light text-secondary me-1" title="Print Invoice" target="_blank"><i class="fas fa-print"></i></a>
                                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-light text-primary me-1" title="Edit Status/Date"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('invoices.delete', $invoice->id) }}" class="btn btn-sm btn-light text-danger" title="Delete Invoice" onclick="return confirm('Are you sure you want to delete this invoice?');"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-file-invoice-dollar fa-3x mb-3 text-light"></i><br>
                                        <h5 class="fw-normal text-secondary mb-1">No invoices found</h5>
                                        <p class="small">Try adjusting your filters or create a new invoice.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($invoices->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-end">
                    {{ $invoices->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
