@extends('layout.app')
@section('title', 'Dashboard Overview')
@section('content')
<style>
    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    
    .text-xs { font-size: .75rem; }
</style>

<div class="container-fluid py-4">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Overview</h1>
    </div>

    <!-- Date Range Filter -->
    <div class="filter-section">
        <form action="{{ route('dashboard') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="start_date" class="col-form-label fw-bold">Start Date:</label>
            </div>
            <div class="col-auto">
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-auto ms-md-4">
                <label for="end_date" class="col-form-label fw-bold">End Date:</label>
            </div>
            <div class="col-auto">
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-auto ms-md-4">
                <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="fas fa-filter me-2"></i>Filter</button>
                <a href="{{ route('dashboard') }}" class="btn btn-light shadow-sm border ms-2">Clear</a>
            </div>
        </form>
    </div>

    <!-- Content Row -->
    <div class="row g-4">

        <!-- Revenue Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h3 mb-0 fw-bold text-gray-800">Rs {{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="card-icon card-icon-lg icon-success">
                                <i class="fas fa-indian-rupee-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Total Orders</div>
                            <div class="h3 mb-0 fw-bold text-gray-800">{{ $totalOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="card-icon card-icon-lg icon-info">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Customers</div>
                            <div class="h3 mb-0 fw-bold text-gray-800">{{ $totalCustomers }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="card-icon card-icon-lg icon-primary">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Products (Catalog)</div>
                            <div class="h3 mb-0 fw-bold text-gray-800">{{ $totalProducts }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="card-icon card-icon-lg icon-warning">
                                <i class="fas fa-box-open"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection