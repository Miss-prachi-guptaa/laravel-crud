<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Employee Management</title>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite([
        'resources/css/app.css',
        'resources/css/employee.css',
        'resources/js/app.js',
        'resources/js/employee.js'
    ])

</head>

<body>

    @yield('content')

</body>
</html>