@if(session('success') || session('error'))
    <div class="global-alert-container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg border-0" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-lg border-0" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <style>
    .global-alert-container {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 9999;
        min-width: 320px;
        max-width: 400px;
    }
    </style>

    <script>
        // Use Vanilla JS so it works on pages without jQuery (like login/register)
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.global-alert-container .alert');
                alerts.forEach(function(alert) {
                    alert.style.transition = "opacity 0.6s ease-out";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 600); // Remove from DOM after fade
                });
            }, 4000);
        });
    </script>
@endif
