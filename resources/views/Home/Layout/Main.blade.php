<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= env('APP_NAME') ?></title>
    <link rel="stylesheet" href="<?= asset('vendors/bootstrap/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendors/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    @yield('css')
</head>

<body class="bg-primary overflow-auto">
    @yield('content')
    <script>
        var base_url = @json(url('/'));
        var current_url = @json(url()->current());
    </script>
    <script src="<?= asset('vendors/fontawesome/js/all.min.js') ?>"></script>
    <script src="<?= asset('vendors/bootstrap/bootstrap.min.js') ?>"></script>
    @yield('js')
</body>

</html>
