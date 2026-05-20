@extends('layout.app')
@section('title', 'Edit Invoice')
@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="h3 mb-0 text-dark fw-bold">Invoice #INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb" class="me-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices') }}" class="text-decoration-none">Invoices</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View/Edit</li>
                </ol>
            </nav>
            <a href="{{ route('invoices') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm me-1"></i> Back
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white pt-4 pb-2 border-bottom-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Invoice Status</h5>
                    @if($invoice->status === 'draft')
                        <span class="badge bg-secondary px-3 py-2">DRAFT</span>
                    @elseif($invoice->status === 'paid')
                        <span class="badge bg-success px-3 py-2">PAID</span>
                    @elseif($invoice->status === 'overdue')
                        <span class="badge bg-danger px-3 py-2">OVERDUE</span>
                    @else
                        <span class="badge bg-warning text-dark px-3 py-2">UNPAID</span>
                    @endif
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('invoices.edit', $invoice->id) }}">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Customer</label>
                                <input type="text" class="form-control" value="{{ $invoice->customer->name ?? 'N/A' }}" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Grand Total</label>
                                <input type="text" class="form-control fw-bold text-dark" value="Rs {{ number_format($invoice->total_amount, 2) }}" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Invoice Date</label>
                                <input type="date" class="form-control" value="{{ $invoice->invoice_date->format('Y-m-d') }}" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Due Date</label>
                                <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" 
                                    value="{{ old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-medium text-secondary">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                    @if($invoice->status === 'draft')
                                        <option value="draft" selected>Draft</option>
                                    @endif
                                    <option value="unpaid" {{ (old('status', $invoice->status) == 'unpaid') ? 'selected' : '' }}>Unpaid</option>
                                    <option value="paid" {{ (old('status', $invoice->status) == 'paid') ? 'selected' : '' }}>Paid</option>
                                    <option value="overdue" {{ (old('status', $invoice->status) == 'overdue') ? 'selected' : '' }}>Overdue</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top gap-2">
                            <button type="submit" name="action" value="save" class="btn btn-light border px-4 shadow-sm text-secondary fw-medium">
                                <i class="fas fa-save me-1"></i> Update Status
                            </button>
                            
                            @if($invoice->status === 'draft')
                            <button type="submit" name="action" value="complete" class="btn btn-primary px-4 shadow-sm fw-medium">
                                <i class="fas fa-check-circle me-1"></i> Complete & Print
                            </button>
                            @else
                            <a href="{{ route('invoices.print', $invoice->id) }}" class="btn btn-primary px-4 shadow-sm fw-medium">
                                <i class="fas fa-print me-1"></i> Print Invoice
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white pt-4 pb-2 border-bottom-0">
                    <h5 class="fw-bold text-dark mb-0">Invoice Items Summary</h5>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Tax (%)</th>
                                    <th>Discount</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->invoiceItems as $item)
                                <tr>
                                    <td class="fw-medium">{{ $item->item->name ?? 'Unknown Item' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rs {{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ number_format($item->tax, 1) }}%</td>
                                    <td class="text-success">-Rs {{ number_format($item->discount, 2) }}</td>
                                    <td class="text-end fw-bold">Rs {{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="5" class="text-end text-muted">Subtotal:</td>
                                    <td class="text-end fw-medium">Rs {{ number_format($invoice->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end text-muted">Total Tax:</td>
                                    <td class="text-end fw-medium">Rs {{ number_format($invoice->total_tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end text-muted">Total Discount:</td>
                                    <td class="text-end fw-medium text-success">-Rs {{ number_format($invoice->total_discount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end fw-bold fs-6">Grand Total:</td>
                                    <td class="text-end fw-bold fs-6 text-dark">Rs {{ number_format($invoice->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
