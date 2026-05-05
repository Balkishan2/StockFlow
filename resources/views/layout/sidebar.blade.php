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
    }

    .content-area {
        flex: 1;
        padding: 25px;
        background: #fafafa;
        min-height: calc(100vh - 70px);
        padding: 0px;
    }


    .sidebar h6 {
        font-size: 12px;
        text-transform: uppercase;
        color: #999;
        margin-bottom: 15px;
    }

    .sidebar a {
        display: block;
        padding: 10px 12px;
        border-radius: 8px;
        color: #444;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 5px;
        transition: 0.2s;
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
    
        <a href="/dashboard" class="active">
            Dashboard
        </a>
    
        <a href="#">
            Orders
        </a>
    
        <a href="#">
            Products
        </a>
    
        <a href="#">
            Customers
        </a>
    
        <h6 class="mt-4">Reports</h6>
    
        <a href="#">
            Sales Report
        </a>
    
        <a href="#">
            Inventory Report
        </a>
    </div>
