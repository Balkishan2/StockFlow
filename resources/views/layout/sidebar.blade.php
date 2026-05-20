<style>
    .app-layout {
        display: flex;
    }

    .layout-wrapper {
        display: flex;
    }

    .sidebar {
        width: 240px;
        min-height: calc(100vh - 70px);
        background: #fff;
        border-right: 1px solid #eee;
        padding: 20px 15px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .sidebar.collapsed {
        width: 70px;
        padding: 20px 10px;
    }

    .sidebar.collapsed span {
        display: none;
    }

    .sidebar.collapsed h6 {
        display: none;
    }

    .sidebar.collapsed a i {
        margin-right: 0;
    }

    .content-area {
        flex: 1;
        padding: 25px;
        background: #fafafa;
        min-height: calc(100vh - 70px);
        padding: 0px;
        transition: all 0.3s ease;
    }


    .sidebar h6 {
        font-size: 12px;
        text-transform: uppercase;
        color: #999;
        margin-bottom: 15px;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        border-radius: 8px;
        color: #444;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 5px;
        transition: 0.2s;
        white-space: nowrap;
    }

    .sidebar a i {
        min-width: 24px;
        text-align: center;
        margin-right: 10px;
        font-size: 16px;
    }

    .sidebar a:hover {
        background: #f5f5f5;
        color: #000;
    }

    .sidebar a.active {
        background: #111;
        color: #fff;
    }

    .main-content {
        flex: 1;
        padding: 25px;
        background: #fafafa;
        min-height: 100vh;
    }
</style>
    <div class="sidebar">
    
        <h6>Main</h6>
    
        <a href="/dashboard" class="sidebarlink {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> <span>Dashboard</span>
        </a>
    
        <a href="{{ route('orders') }}" class="sidebarlink {{ request()->is('orders*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i> <span>Orders</span>
        </a>

        <a href="{{ route('invoices') }}" class="sidebarlink {{ request()->is('invoices*') ? 'active' : '' }}">
            <i class="fas fa-file-invoice-dollar"></i> <span>Sales Invoices</span>
        </a>
    
        <a href="{{ route('products.listing') }}" class="sidebarlink {{ request()->is('products*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> <span>Products</span>
        </a>
    
        <a href="{{ route('customers') }}" class="sidebarlink {{ request()->is('customers*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> <span>Customers</span>
        </a>
        
        <a href="{{ route('inventory') }}" class="sidebarlink {{ request()->is('inventory*') || request()->is('item-inventory*') ? 'active' : '' }}">
            <i class="fas fa-warehouse"></i> <span>Item Inventory</span>
        </a>
    
        <h6 class="mt-4">Reports</h6>
    
        <a href="#">
            <i class="fas fa-chart-line"></i> <span>Sales Report</span>
        </a>
    
        <a href="#">
            <i class="fas fa-warehouse"></i> <span>Inventory Report</span>
        </a>
    </div>
   
    
