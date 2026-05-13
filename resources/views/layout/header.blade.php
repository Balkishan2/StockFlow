<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f4f7f6;
        color: #1e293b;
    }
    
    .custom-navbar {
        background: #ffffff;
        border-bottom: 1px solid #f0f0f0;
        padding: 12px 0;
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 20px;
        color: #111 !important;
        letter-spacing: -0.5px;
    }

    .nav-link {
        color: #64748b !important;
        font-weight: 500;
        margin-right: 18px;
        transition: color 0.2s ease;
    }

    .nav-link:hover {
        color: #0f172a !important;
    }

    .nav-right {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-right: 20px;
    }

    .user-name {
        font-weight: 600;
        color: #334155;
    }

    .avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 10px rgba(79, 70, 229, 0.3);
    }

    .logout-btn {
        border: none;
        background: transparent;
        color: #94a3b8;
        font-size: 14px;
        font-weight: 500;
        transition: 0.2s;
    }

    .logout-btn:hover {
        color: #e11d48;
    }

    .box-shadow {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
    }

    .logoDiv {
        margin-left: 20px;
    }

    /* PREMIUM SAAS OVERRIDES */
    .card {
        border-radius: 16px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.02) !important;
        background: #ffffff;
    }
    
    .card-header {
        background-color: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 24px 24px 16px;
    }

    .card-body {
        padding: 24px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 500;
        color: #334155;
        background-color: #f8fafc;
        transition: all 0.2s ease;
        box-shadow: none !important;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
    }
    
    .form-control::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    .form-label {
        font-weight: 600;
        font-size: 13px;
        color: #475569;
        margin-bottom: 8px;
    }

    .btn {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border: none;
        color: #fff;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    }

    .btn-dark {
        background-color: #0f172a;
        border: none;
    }

    .btn-dark:hover {
        background-color: #000000;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }
    
    .btn-outline-secondary {
        border-color: #e2e8f0;
        color: #475569;
        background: #ffffff;
    }
    
    .btn-outline-secondary:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    /* TABLE OVERRIDES */
    .table {
        margin-bottom: 0;
    }
    
    .table > :not(caption) > * > * {
        padding: 16px 20px;
        border-bottom-color: #f1f5f9;
        vertical-align: middle;
    }

    .table-light th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .input-group-text {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-weight: 600;
        border-radius: 10px;
    }
    
    .breadcrumb-item a {
        color: #64748b;
        font-weight: 500;
    }
    
    .breadcrumb-item.active {
        color: #0f172a;
        font-weight: 600;
    }
</style>

<nav class="navbar navbar-expand-lg custom-navbar box-shadow">
    <div class="logoDiv d-flex align-items-center">
      
        <a class="navbar-brand" href="/dashboard">
            StockFlow
        </a>
    </div>

    <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navClean">
        <span class="navbar-toggler-icon"></span>
    </button> -->
    <div class="collapse navbar-collapse d-flex justify-content-between w-100" id="navClean">
        <button id="sidebarToggle" class="btn btn-sm btn-light ms-3">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-right ms-auto">

            <span class="user-name">
                {{ Auth::user()->name }}
            </span>

            <div class="avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">Logout</button>
            </form>

        </div>

    </div>
</nav>
<div class="body-box" style="display: flex;">

