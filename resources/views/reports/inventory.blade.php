@extends('layout.app')
@section('title', 'Inventory Report')
@section('content')

<div class="container-fluid py-4">
    <!-- Page Header Row -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="h3 mb-0 text-dark fw-bold">Inventory Report</h3>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Inventory Report</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-4">
            <form method="GET" action="{{ route('reports.inventory') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Item</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control border-start-0" id="search" name="search" placeholder="Search by name or SKU..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Stock Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Stock Health</option>
                        <option value="in" {{ $statusFilter === 'in' ? 'selected' : '' }}>In Stock (>= 10)</option>
                        <option value="low" {{ $statusFilter === 'low' ? 'selected' : '' }}>Low Stock (< 10)</option>
                        <option value="out" {{ $statusFilter === 'out' ? 'selected' : '' }}>Out of Stock (0)</option>
                    </select>
                </div>
                <div class="col-md-5 d-flex justify-content-md-end justify-content-start gap-2">
                    <button type="submit" class="btn btn-dark px-4">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('reports.inventory') }}" class="btn btn-outline-secondary px-3">
                        <i class="fas fa-sync-alt me-1"></i>Reset
                    </a>
                    <a href="{{ route('reports.inventory.export', request()->query()) }}" class="btn btn-success px-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                        <i class="fas fa-file-csv me-2"></i>Export CSV
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="row row-cols-1 row-cols-md-5 g-3 mb-4">
        <!-- Unique Items Card -->
        <div class="col">
            <div class="card reports-card border-left-indigo shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2 text-truncate">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1 text-truncate" style="font-size: 11px; letter-spacing: 0.5px;">
                                Unique Items
                            </div>
                            <div class="fw-bold text-gray-800 text-truncate" style="font-size: 17px; color: #1e293b;">
                                {{ number_format($totalItems) }}
                            </div>
                        </div>
                        <div class="card-icon icon-indigo">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Cost Value Card -->
        <div class="col">
            <div class="card reports-card border-left-blue shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2 text-truncate">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1 text-truncate" style="font-size: 11px; letter-spacing: 0.5px;">
                                Cost Value
                            </div>
                            <div class="fw-bold text-gray-800 text-truncate" style="font-size: 17px; color: #1e293b;" title="Rs {{ number_format($totalCostValue, 2) }}">
                                Rs {{ number_format($totalCostValue, 2) }}
                            </div>
                        </div>
                        <div class="card-icon icon-blue">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Retail Value Card -->
        <div class="col">
            <div class="card reports-card border-left-green shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2 text-truncate">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1 text-truncate" style="font-size: 11px; letter-spacing: 0.5px;">
                                Retail Value
                            </div>
                            <div class="fw-bold text-gray-800 text-truncate" style="font-size: 17px; color: #1e293b;" title="Rs {{ number_format($totalRetailValue, 2) }}">
                                Rs {{ number_format($totalRetailValue, 2) }}
                            </div>
                        </div>
                        <div class="card-icon icon-green">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Potential Profit Card -->
        <div class="col">
            <div class="card reports-card border-left-amber shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2 text-truncate">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1 text-truncate" style="font-size: 11px; letter-spacing: 0.5px;">
                                Potential Profit
                            </div>
                            <div class="fw-bold text-gray-800 text-truncate" style="font-size: 17px; color: #1e293b;" title="Rs {{ number_format($potentialProfit, 2) }}">
                                Rs {{ number_format($potentialProfit, 2) }}
                            </div>
                        </div>
                        <div class="card-icon icon-amber">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low / Out Stock Health Card -->
        <div class="col">
            <div class="card reports-card border-left-red shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-2 text-truncate">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1 text-truncate" style="font-size: 11px; letter-spacing: 0.5px;">
                                Stock Alerts
                            </div>
                            <div class="fw-bold text-gray-800 text-truncate" style="font-size: 14px; color: #ef4444;">
                                Low: <strong>{{ $lowStockCount }}</strong> | Out: <strong>{{ $outOfStockCount }}</strong>
                            </div>
                        </div>
                        <div class="card-icon icon-red">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
            <h5 class="fw-bold text-dark mb-0">Stock Health Ledger</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Item ID</th>
                            <th>SKU</th>
                            <th>Item Name</th>
                            <th>Current Stock</th>
                            <th>Cost Price</th>
                            <th>Selling Price</th>
                            <th>Total Cost Value</th>
                            <th>Total Retail Value</th>
                            <th>Potential Profit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventoryData as $item)
                        @php
                            $totalCost = $item->current_stock * $item->cost_price;
                            $totalRetail = $item->current_stock * $item->selling_price;
                            $profit = $totalRetail - $totalCost;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="fw-medium text-dark">#{{ $item->id }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark font-monospace border px-2 py-1" style="font-size: 11px;">{{ $item->sku }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->name }}</div>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ number_format($item->current_stock) }}</span>
                            </td>
                            <td>Rs {{ number_format($item->cost_price, 2) }}</td>
                            <td>Rs {{ number_format($item->selling_price, 2) }}</td>
                            <td class="text-primary">Rs {{ number_format($totalCost, 2) }}</td>
                            <td class="text-success">Rs {{ number_format($totalRetail, 2) }}</td>
                            <td class="fw-bold text-dark">Rs {{ number_format($profit, 2) }}</td>
                            <td>
                                @if($item->current_stock == 0)
                                    <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">OUT OF STOCK</span>
                                @elseif($item->current_stock < 10)
                                    <span class="badge bg-warning-subtle text-warning px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">LOW STOCK</span>
                                @else
                                    <span class="badge bg-success-subtle text-success px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">IN STOCK</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fas fa-warehouse fa-3x mb-3 text-light"></i><br>
                                <h5 class="fw-normal text-secondary mb-1">No items found in inventory</h5>
                                <p class="small">Try adjusting your filters or search keywords.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if($inventoryData->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-end">
            {{ $inventoryData->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
