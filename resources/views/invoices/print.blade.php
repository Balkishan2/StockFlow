<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</title>
    <!-- Use standard fonts -->
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 40px;
            background-color: #fff;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
        }
        .header h1 {
            margin: 0;
            font-size: 36px;
            color: #1e293b;
        }
        .company-info {
            text-align: right;
            color: #64748b;
        }
        .company-info h2 {
            margin: 0;
            color: #1e293b;
            font-size: 20px;
        }
        .details-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .bill-to h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            text-transform: uppercase;
            color: #94a3b8;
        }
        .invoice-details table {
            width: auto;
        }
        .invoice-details td {
            padding: 4px 15px 4px 0;
        }
        .invoice-details td:first-child {
            color: #64748b;
            font-weight: bold;
        }
        table.items-table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.items-table th {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px;
            font-size: 14px;
            color: #64748b;
            text-transform: uppercase;
        }
        table.items-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .text-right {
            text-align: right;
        }
        .totals-box {
            width: 350px;
            margin-left: auto;
            border-top: 2px solid #e2e8f0;
            padding-top: 20px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 15px;
            color: #64748b;
        }
        .grand-total {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #fff;
            background-color: #f59e0b; /* default warning for unpaid */
            margin-top: 10px;
        }
        .badge.paid { background-color: #10b981; }
        .badge.overdue { background-color: #ef4444; }
        .badge.draft { background-color: #64748b; }

        @media print {
            body {
                padding: 0;
                background-color: transparent;
            }
            .invoice-box {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="invoice-box">
        <div class="header">
            <div>
                <h1>INVOICE</h1>
                @if($invoice->status === 'paid')
                    <div class="badge paid">PAID</div>
                @elseif($invoice->status === 'overdue')
                    <div class="badge overdue">OVERDUE</div>
                @elseif($invoice->status === 'draft')
                    <div class="badge draft">DRAFT</div>
                @else
                    <div class="badge">UNPAID</div>
                @endif
            </div>
            <div class="company-info">
                <h2>StockFlow Inc.</h2>
                <p style="margin: 5px 0;">123 Business Avenue<br>Commerce City, CC 12345<br>billing@stockflow.com</p>
            </div>
        </div>

        <div class="details-row">
            <div class="bill-to">
                <h3>Bill To</h3>
                <strong>{{ $invoice->customer->name ?? 'N/A' }}</strong><br>
                @if($invoice->customer && $invoice->customer->address)
                    {{ $invoice->customer->address }}<br>
                @endif
                @if($invoice->customer && $invoice->customer->phone)
                    Phone: {{ $invoice->customer->phone }}<br>
                @endif
                @if($invoice->customer && $invoice->customer->email)
                    Email: {{ $invoice->customer->email }}
                @endif
            </div>
            <div class="invoice-details">
                <table>
                    <tr>
                        <td>Invoice #:</td>
                        <td>INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                    </tr>
                    @if($invoice->due_date)
                    <tr>
                        <td>Due Date:</td>
                        <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Tax</th>
                    <th>Discount</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->invoiceItems as $item)
                <tr>
                    <td>{{ $item->item->name ?? 'Item' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rs {{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->tax, 1) }}%</td>
                    <td>-Rs {{ number_format($item->discount, 2) }}</td>
                    <td class="text-right fw-bold">Rs {{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-box">
            <div class="totals-row">
                <span>Subtotal:</span>
                <span>Rs {{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            <div class="totals-row">
                <span>Total Tax:</span>
                <span>Rs {{ number_format($invoice->total_tax, 2) }}</span>
            </div>
            <div class="totals-row" style="color: #10b981;">
                <span>Total Discount:</span>
                <span>-Rs {{ number_format($invoice->total_discount, 2) }}</span>
            </div>
            <div class="grand-total">
                <span>Grand Total:</span>
                <span>Rs {{ number_format($invoice->total_amount, 2) }}</span>
            </div>
        </div>
        
        <div style="margin-top: 80px; text-align: center; color: #94a3b8; font-size: 14px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            Thank you for your business!
        </div>
    </div>

</body>
</html>
