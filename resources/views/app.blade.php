<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="{{ asset('favicon.png') }}" rel="icon">

  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
  @inertiaHead
  <title>PHenlínea</title>
</head>

<body>
  @inertia
</body>

</html>