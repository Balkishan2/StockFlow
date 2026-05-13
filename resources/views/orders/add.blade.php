@extends('layout.app')
@section('title', 'Add Order')
@section('content')

<style>
    /* Sleek Invoice Item Inputs */
    .invoice-input {
        border: 1px solid transparent !important;
        background-color: transparent !important;
        box-shadow: none !important;
        border-radius: 6px;
        padding: 8px 12px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .invoice-input:hover {
        background-color: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
    .invoice-input:focus {
        background-color: #fff !important;
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
    }
    .invoice-table th {
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.8px;
        color: #94a3b8;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 12px;
    }
    .invoice-table td {
        vertical-align: middle;
        padding: 12px 8px;
        border-bottom: 1px solid #f8fafc;
    }
    
    /* Sleek Summary Card */
    .summary-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border: 1px solid #f1f5f9;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .summary-row:last-child {
        border-bottom: none;
    }
    .summary-label {
        color: #64748b;
        font-weight: 500;
        font-size: 14px;
    }
    .summary-value {
        font-weight: 600;
        color: #1e293b;
        font-size: 15px;
    }
    .grand-total-box {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #fff;
        border-radius: 14px;
        padding: 24px;
        margin-top: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
    }
    .grand-total-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #cbd5e1;
        margin-bottom: 6px;
        display: block;
    }
    .grand-total-value {
        font-size: 36px;
        font-weight: 700;
        letter-spacing: -1px;
    }
    
    .remove-btn {
        background: transparent;
        border: none;
        color: #cbd5e1;
        transition: 0.2s;
    }
    .remove-btn:hover:not(:disabled) {
        color: #ef4444;
        transform: scale(1.1);
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="h3 mb-0 text-dark fw-bold">Create Order</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb" class="me-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/orders" class="text-decoration-none">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New Order</li>
                </ol>
            </nav>
            <a href="/orders" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-times fa-sm me-1"></i> Cancel
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('orders.add') }}" id="orderForm">
        @csrf
        <div class="row">
            <!-- Left Column: Details & Items -->
            <div class="col-xl-8 col-lg-7">
                
                <!-- Customer Section -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                        <h6 class="m-0 fw-bold text-dark fs-5">Customer Information</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Customer Name</label>
                                <input type="text" class="form-control" name="customer_name" placeholder="E.g. Acme Corp" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="customer_email" placeholder="contact@acme.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="customer_phone" placeholder="+1 555 000 0000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Shipping Address</label>
                                <input type="text" class="form-control" name="shipping_address" placeholder="123 Business Rd.">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Section -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="mt-2">
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold" id="addItemBtn">
                                <i class="fas fa-plus me-1"></i> Add Another Item
                            </button>
                        </div>
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-dark fs-5">Order Items</h6>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="table-responsive overflow-visible">
                            <table class="table invoice-table" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Item Description</th>
                                        <th style="width: 15%">Qty</th>
                                        <th style="width: 20%">Price</th>
                                        <th style="width: 15%">Tax %</th>
                                        <th style="width: 10%" class="text-end">Total</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    <tr class="item-row">
                                        <td>
                                            <input type="text" class="form-control invoice-input item-search" name="items[0][name]" placeholder="Enter item name..." required autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control invoice-input item-qty" name="items[0][qty]" value="1" min="1" required>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="text-muted me-1">Rs</span>
                                                <input type="number" class="form-control invoice-input item-price px-1" name="items[0][price]" value="0.00" step="0.01" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <input type="number" class="form-control invoice-input item-tax px-1" name="items[0][tax]" value="0" min="0" step="0.1">
                                                <span class="text-muted ms-1">%</span>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold row-total align-middle" style="color: #334155;">Rs0.00</td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="remove-btn remove-item-btn p-1" disabled>
                                                <i class="fas fa-times fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>

            </div>

            <!-- Right Column: Summary -->
            <div class="col-xl-4 col-lg-5">
                <div class="card summary-card border-0 mb-4 position-sticky" style="top: 20px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-4 fs-5">Order Summary</h6>
                        
                        <div class="summary-row">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value" id="summary-subtotal">Rs0.00</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Estimated Tax</span>
                            <span class="summary-value" id="summary-tax">Rs0.00</span>
                        </div>
                        <div class="summary-row border-bottom-0 pb-0">
                            <span class="summary-label">Discount</span>
                            <div class="d-flex align-items-center" style="width: 100px;">
                                <span class="text-muted me-2">Rs</span>
                                <input type="number" class="form-control invoice-input text-end px-1" id="summary-discount" name="discount" value="0.00" step="0.01">
                            </div>
                        </div>

                        <div class="grand-total-box">
                            <span class="grand-total-label">Grand Total</span>
                            <div class="grand-total-value" id="summary-grand-total">Rs0.00</div>
                        </div>

                        <div class="mt-4 mb-4">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select class="form-select border-0 shadow-sm" id="status" name="status" style="background-color: #fff; padding: 12px;">
                                <option value="pending" selected>Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 shadow-sm rounded-3" style="font-size: 15px;">
                            Create Order <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    let itemIndex = 1;
    const $itemsBody = $('#itemsBody');

    // Add new row logic
    $('#addItemBtn').on('click', function() {
        const newRow = `
            <tr class="item-row">
                <td>
                    <input type="text" class="form-control invoice-input item-search" name="items[${itemIndex}][name]" placeholder="Enter item name..." required autocomplete="off">
                </td>
                <td>
                    <input type="number" class="form-control invoice-input item-qty" name="items[${itemIndex}][qty]" value="1" min="1" required>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-1">Rs</span>
                        <input type="number" class="form-control invoice-input item-price px-1" name="items[${itemIndex}][price]" value="0.00" step="0.01" required>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <input type="number" class="form-control invoice-input item-tax px-1" name="items[${itemIndex}][tax]" value="0" min="0" step="0.1">
                        <span class="text-muted ms-1">%</span>
                    </div>
                </td>
                <td class="text-end fw-bold row-total align-middle" style="color: #334155;">Rs0.00</td>
                <td class="text-center align-middle">
                    <button type="button" class="remove-btn remove-item-btn p-1">
                        <i class="fas fa-times fs-5"></i>
                    </button>
                </td>
            </tr>
        `;
        $itemsBody.append(newRow);
        itemIndex++;
        updateTotals();
        checkRemoveButtons();
    });

    // Delegate input events to dynamically recalculate totals
    $itemsBody.on('input', '.item-qty, .item-price, .item-tax', function() {
        updateTotals();
    });

    // Delegate click events to handle row removal
    $itemsBody.on('click', '.remove-item-btn', function() {
        $(this).closest('.item-row').remove();
        updateTotals();
        checkRemoveButtons();
    });

    // Recalculate on discount change
    $('#summary-discount').on('input', updateTotals);

    // Disable the delete button if only one row remains
    function checkRemoveButtons() {
        const $rows = $('.item-row');
        if ($rows.length === 1) {
            $rows.find('.remove-item-btn').prop('disabled', true);
        } else {
            $rows.find('.remove-item-btn').prop('disabled', false);
        }
    }

    // Core calculation logic
    function updateTotals() {
        let subtotal = 0;
        let totalTax = 0;

        $('.item-row').each(function() {
            const qty = parseFloat($(this).find('.item-qty').val()) || 0;
            const price = parseFloat($(this).find('.item-price').val()) || 0;
            const taxRate = parseFloat($(this).find('.item-tax').val()) || 0;

            const lineTotal = qty * price;
            const lineTax = lineTotal * (taxRate / 100);

            subtotal += lineTotal;
            totalTax += lineTax;

            // Update individual row total
            $(this).find('.row-total').text('Rs' + (lineTotal + lineTax).toFixed(2));
        });

        const discount = parseFloat($('#summary-discount').val()) || 0;
        const grandTotal = subtotal + totalTax - discount;

        // Update the global summary side panel
        $('#summary-subtotal').text('Rs' + subtotal.toFixed(2));
        $('#summary-tax').text('Rs' + totalTax.toFixed(2));
        $('#summary-grand-total').text('Rs' + Math.max(0, grandTotal).toFixed(2));
    }

    // Initialize state on load
    checkRemoveButtons();
    updateTotals();
});
</script>
@endsection