<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>

<body class="bg-gradient">

    <div class="container center-flex">
        <div class="card login-card">

            <h2 class="title text-center">Welcome Back</h2>
            <p class="subtitle text-center">Login to your account</p>

            <form action="{{ route('login') }}" method="post">
                @csrf
                @include('layout.alert')
                <div class="form-group" style="display: flex; align-items: center; ">
                    <input type="email" class="input" name="email" placeholder="Enter your email" require>
                </div>

                <div class="form-group">
                    <input type="password" class="input" name="password" placeholder="Enter your password" require>
                </div>
                <button class="btn btn-login btn-block">Login</button>
            </form>

            <p class="text-center mt-20">
                Don't have an account?
                <a href="{{ route('register') }}" class="link">Signup</a>
            </p>

        </div>
    </div>

</body>

</html>
<script>

</script>