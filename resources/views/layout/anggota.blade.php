<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Web Anggota</title>
    @include('layout.header')
    <link href="{{ asset('assets/css/style-anggota.css') }}" rel="stylesheet" />
</head>

<body>
    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            @include('layout.nav')
            @yield('content_anggota')
        </div>
        @include('anggota.nav')
    </div>
    <!-- End of Content Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script>
        const list = document.querySelectorAll(".list");

        function activeLink() {
            list.forEach((item) => item.classList.remove("active"));
            this.classList.add("active");
        }

        list.forEach((item) => item.addEventListener("click", activeLink));
    </script>
</body>

</html>
