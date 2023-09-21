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

<body>
    <div id="dropzone" class="dropzone"></div>
    <div class="row background-nav">
        <div class="col-md-3 bg-white">
            @include('Dashboard.Layout.Side')
        </div>
        <div class="col-md-9">
            @include('Dashboard.Layout.Nav')
            <div class="container pt-3 mb-5">
                @yield('content')
            </div>
        </div>
    </div>
    <script>
    var base_url = @json(url('/'));
    var current_url = @json(url()->current());
    </script>
    <script src="<?= asset('vendors/fontawesome/js/all.min.js') ?>"></script>
    <script src="<?= asset('vendors/bootstrap/bootstrap.min.js') ?>"></script>
    @yield('js')
</body>

</html>
