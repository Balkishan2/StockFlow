@extends('layout.app')
@section('title', 'Customers')
@section('content')

<div class="container-fluid py-4">
    <!-- Page Header Row -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="h3 mb-0 text-dark fw-bold">Customers</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb" class="me-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">Customers</li>
                </ol>
            </nav>
            <a href="{{ route('customers.add') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Add Customer
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
                    <form method="GET" action="{{ route('customers') }}" class="row g-3 align-items-center">
                        <div class="col-lg-4 col-md-12">
                            <label for="search" class="visually-hidden">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone">
                            </div>
                        </div>
                        <div class="col-lg-auto col-md-4 d-flex align-items-center">
                            <button type="submit" class="btn btn-dark px-4 shadow-sm border-0">
                                Filter
                            </button>
                            <a href="{{ route('customers') }}" class="text-muted text-decoration-none ms-3" style="font-size: 14px; transition: color 0.2s;" onmouseover="this.className='text-danger text-decoration-none ms-3'" onmouseout="this.className='text-muted text-decoration-none ms-3'">
                                <i class="fas fa-times me-1"></i>Clear
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Table Section -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $customer->id }}</td>
                                    <td class="fw-medium text-dark">{{ $customer->name }}</td>
                                    <td>{{ $customer->email ?? '-' }}</td>
                                    <td>{{ $customer->phone ?? '-' }}</td>
                                    <td class="text-truncate" style="max-width: 200px;" title="{{ $customer->address }}">{{ $customer->address ?? '-' }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-light text-primary me-1" title="Edit Customer"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('customers.delete', $customer->id) }}" class="btn btn-sm btn-light text-danger" title="Delete Customer" onclick="return confirm('Are you sure you want to delete this customer?');"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-users fa-3x mb-3 text-light"></i><br>
                                        <h5 class="fw-normal text-secondary mb-1">No customers found</h5>
                                        <p class="small">Try adjusting your filters or add a new customer.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination -->
                @if($customers->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-end">
                    {{ $customers->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
