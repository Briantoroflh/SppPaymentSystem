<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" />
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css"
        rel="stylesheet" />
</head>

<body class="bg-base-200 font-sans">
    <div class="flex min-h-screen">
        <!-- SIDEBAR -->
        @include('layouts.sidebar')
        <!-- END SIDEBAR -->

        <!-- Main Content -->
        <main class="flex-1 md:ml-0">
            @yield('section')
        </main>
    </div>

    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
</body>

</html>