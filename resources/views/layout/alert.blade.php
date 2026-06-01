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
        (function() {
            function fadeAndRemove(alert) {
                if (!alert) return;
                alert.style.transition = "opacity 0.4s ease-out, transform 0.4s ease-out";
                alert.style.opacity = "0";
                alert.style.transform = "translateY(-10px)";
                setTimeout(function() {
                    alert.remove();
                    const container = document.querySelector('.global-alert-container');
                    if (container && container.querySelectorAll('.alert').length === 0) {
                        container.remove();
                    }
                }, 400);
            }

            // Close button click listener using event delegation
            document.addEventListener('click', function(e) {
                const dismissBtn = e.target.closest('[data-bs-dismiss="alert"]');
                if (dismissBtn) {
                    const alert = dismissBtn.closest('.alert');
                    fadeAndRemove(alert);
                }
            });

            // Automatic dismissal
            function initAutoDismiss() {
                setTimeout(function() {
                    const alerts = document.querySelectorAll('.global-alert-container .alert');
                    alerts.forEach(function(alert) {
                        fadeAndRemove(alert);
                    });
                }, 4000);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initAutoDismiss);
            } else {
                initAutoDismiss();
            }
        })();
    </script>
@endif
