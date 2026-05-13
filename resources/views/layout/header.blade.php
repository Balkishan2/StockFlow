<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>
<style>
    .custom-navbar {
        background: #ffffff;
        border-bottom: 1px solid #eee;
        padding: 12px 0;
    }

    .navbar-brand {
        font-weight: 600;
        font-size: 18px;
        color: #111 !important;
    }

    .nav-link {
        color: #555 !important;
        font-weight: 500;
        margin-right: 18px;
        transition: color 0.2s ease;
    }

    .nav-link:hover {
        color: #000 !important;
    }

    .nav-right {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-right: 20px;
    }

    .user-name {
        font-weight: 500;
        color: #333;
    }

    .avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #111;
        color: #fff;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logout-btn {
        border: none;
        background: transparent;
        color: #888;
        font-size: 14px;
        transition: 0.2s;
    }

    .logout-btn:hover {
        color: #e63946;
    }
    .box-shadow{
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }
    .logoDiv{
        margin-left: 20px;
    }
</style>

<nav class="navbar navbar-expand-lg custom-navbar box-shadow">
    <div class="logoDiv d-flex align-items-center">
        <button id="sidebarToggle" class="btn btn-sm btn-light me-2">
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand" href="/dashboard">
            StockFlow
        </a>
    </div>

    <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navClean">
        <span class="navbar-toggler-icon"></span>
    </button> -->
    <div class="collapse navbar-collapse" id="navClean">

        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link sidebarlink" href="/dashboard">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebarlink" href="#">Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebarlink" href="#">Reports</a>
            </li>
        </ul>

        <div class="nav-right">

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

