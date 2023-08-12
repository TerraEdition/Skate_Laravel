<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= env('APP_NAME') ?></title>
    <link rel="stylesheet" href="<?= asset('vendors/bootstrap/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    @yield('css')
    <link rel="icon" type="image/x-icon" href="<?= asset('storage/image/logo.png') ?>">
    <style>
        <style>body {
            margin: 0;
            padding: 0;
        }

        .bg-auth {
            position: relative;
            min-height: 100vh;
        }

        .bg-auth::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 55vw;
            height: 100vh;
            /* background-image: url('{{ asset('storage/image/bg-auth.jpg') }}'); */
            background-position: top left;
            background-repeat: no-repeat;
            background-size: cover;
            border-bottom-right-radius: 100px;
            z-index: -2;
        }

        .border-radius {
            border-radius: 100px;
            padding: 25px;
            width: 200px;
            background: white;
            margin: auto;
        }

        .logo {
            height: 150px;
            width: 120px;
        }
    </style>
</head>

<body>
    <div class="row bg-auth">
        <div class="col-md-7">
            <div class="container text-center">
                <div class="border-radius text-center my-5">
                    <img src="{{ asset('storage/image/logo.png') }}" alt="logo" class="img-fluid logo">
                </div>
                <div class="mt-5">
                    <h1>{{ env('APP_NAME') }}</h1>
                </div>
                <div class="text-center fw-bold mt-5 py-2 border border-light border-1 w-50 m-auto">
                    DASHBOARD
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="container my-5 px-5">
                @if (session('msg'))
                    <div class="alert alert-danger">
                        {{ session('msg') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
    <script src="<?= asset('vendors/bootstrap/bootstrap.min.js') ?>"></script>
</body>

</html>
