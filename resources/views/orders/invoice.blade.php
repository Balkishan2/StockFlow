<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .invoice-box {
            max-width: 800px;
            margin: 40px auto;
            padding: 40px;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-radius: 8px;
        }
        .invoice-header {
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .invoice-title {
            font-size: 36px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .text-muted {
            color: #64748b !important;
        }
        .table th {
            border-bottom: 2px solid #e2e8f0;
            color: #64748b;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .table td {
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .total-row {
            font-weight: 700;
            font-size: 18px;
        }
        .grand-total-row {
            font-weight: 800;
            font-size: 24px;
            color: #0f172a;
            border-top: 2px solid #1e293b !important;
        }
        @media print {
            body {
                background: #fff;
            }
            .invoice-box {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Success Message for the redirect, visible briefly before print -->
    <div class="container mt-3 no-print">
        @if(session('success'))
            <div class="alert alert-success shadow-sm border-0">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
        <div class="text-end mb-3">
            <a href="{{ route('orders') }}" class="btn btn-secondary me-2">Back to Orders</a>
            <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print me-1"></i> Print Invoice</button>
        </div>
    </div>

    <div class="invoice-box">
        <div class="invoice-header d-flex justify-content-between align-items-start">
            <div>
                <h1 class="invoice-title">Invoice</h1>
                <p class="text-muted mb-0">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-muted">Date: {{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</p>
            </div>
            <div class="text-end">
                <h4 class="fw-bold text-dark">StockFlow Inc.</h4>
                <p class="text-muted mb-0">123 Business Avenue<br>Tech City, TC 10101<br>contact@stockflow.com</p>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-6">
                <h6 class="text-muted text-uppercase fw-bold mb-2" style="font-size: 12px;">Bill To:</h6>
                <h5 class="fw-bold mb-1">{{ $order->customer->name }}</h5>
                <p class="text-muted mb-0">
                    {{ $order->customer->address ?? 'No Address Provided' }}<br>
                    {{ $order->customer->phone ?? 'No Phone' }}<br>
                    {{ $order->customer->email ?? 'No Email' }}
                </p>
            </div>
        </div>

        <table class="table mb-5">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Price</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                    $tax = 0;
                @endphp
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->item->name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">Rs {{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-end">Rs {{ number_format($item->total, 2) }}</td>
                    </tr>
                    @php
                        // Backwards calculating tax from total vs unit_price * qty
                        $lineRawTotal = $item->quantity * $item->unit_price;
                        $lineTax = $item->total - $lineRawTotal;
                        $subtotal += $lineRawTotal;
                        $tax += $lineTax;
                    @endphp
                @endforeach
            </tbody>
        </table>

        <div class="row justify-content-end">
            <div class="col-5">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted fw-bold">Subtotal</td>
                        <td class="text-end fw-bold">Rs {{ number_format($subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Estimated Tax</td>
                        <td class="text-end fw-bold">Rs {{ number_format($tax, 2) }}</td>
                    </tr>
                    @if($order->total_amount < ($subtotal + $tax))
                    <tr>
                        <td class="text-muted fw-bold text-danger">Discount</td>
                        <td class="text-end fw-bold text-danger">- Rs {{ number_format(($subtotal + $tax) - $order->total_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="grand-total-row">
                        <td>Total</td>
                        <td class="text-end">Rs {{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="text-center mt-5 pt-4" style="border-top: 1px solid #e2e8f0;">
            <p class="text-muted fw-bold">Thank you for your business!</p>
        </div>
    </div>

    <!-- Automatically open print dialog -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); // small delay to ensure styles are loaded
        };
    </script>
</body>
</html>
