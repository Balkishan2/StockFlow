@extends('layout.app')
@section('title', 'Users')
@section('content')

<div class="container-fluid py-4">
    <!-- Page Header Row -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <h3 class="h3 mb-0 text-dark fw-bold">Users</h3>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center">
            <nav aria-label="breadcrumb" class="me-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </nav>
            <a href="{{ route('users.add') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-user-plus fa-sm text-white-50 me-1"></i> Add User
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
                    <form method="GET" action="{{ route('users') }}" class="row g-3 align-items-center">
                        <div class="col-lg-4 col-md-12">
                            <label for="search" class="visually-hidden">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or mobile">
                            </div>
                        </div>
                        <div class="col-lg-auto col-md-4 d-flex align-items-center">
                            <button type="submit" class="btn btn-dark px-4 shadow-sm border-0">
                                Filter
                            </button>
                            <a href="{{ route('users') }}" class="text-muted text-decoration-none ms-3" style="font-size: 14px; transition: color 0.2s;" onmouseover="this.className='text-danger text-decoration-none ms-3'" onmouseout="this.className='text-muted text-decoration-none ms-3'">
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
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Joined Date</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2" style="width: 32px; height: 32px; font-size: 12px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <span class="fw-medium text-dark">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->mobile }}</td>
                                    <td>{{ $user->created_at->format('d M, Y') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-light text-primary me-1" title="Edit User"><i class="fas fa-edit"></i></a>
                                        @if(Auth::id() != $user->id)
                                        <form action="{{ route('users.delete', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete User">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @else
                                        <button class="btn btn-sm btn-light text-muted" title="You cannot delete yourself" disabled><i class="fas fa-trash-alt"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-users fa-3x mb-3 text-light"></i><br>
                                        <h5 class="fw-normal text-secondary mb-1">No users found</h5>
                                        <p class="small">Try adjusting your filters or add a new user.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination -->
                @if($users->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-end">
                    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
