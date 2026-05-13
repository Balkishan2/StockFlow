</div>

@yield('footer')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
            });
        }
    });
    $(document).ready(function(){
        $('.sidebarlink').click(function(){
            console.log("hello");
            $('.sidebarlink').removeClass('active');
            $(this).addClass('active');
        })
    })
</script>

</body>
</html>