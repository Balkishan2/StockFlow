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

            <h2 class="title text-center">Welcome</h2>
            <p class="subtitle text-center">Register your account</p>

            <form action="{{ route('register') }}" method="post">
                @csrf
                 @if (session('error'))
                    <div class="alert alert-danger text-center">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="form-group">
                    <label class="label">Name</label>
                    <input type="text" class="input" name="name" placeholder="Enter your name" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                 <div class="form-group">
                    <label class="label">Mobile No</label>
                    <input type="number" class="input" name="mobile" placeholder="Enter your mobile no" required>
                    @error('mobile')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="label">Email</label>
                    <input type="email" class="input" name="email" placeholder="Enter your email" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="label">Password</label>
                    <input type="password" class="input" name="password" placeholder="Enter your password" required>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-login btn-block">Register</button>
            </form>

            <p class="text-center mt-20">
                Already have an account? <a href="{{ route('login') }}" class="link">Login</a>
            </p>

        </div>
    </div>

</body>

</html>