<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Web Admin</title>
    <link rel="icon" href="{{ asset('assets/img/logo-koperasi.png') }}" type="image/x-icon">

    @include('layout.header')
</head>

<body>
    <!-- Page Wrapper -->
    <div id="wrapper">
        @include('layout.sidebar')
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            @include('layout.nav')
            @yield('content')
            @include('layout.footer')
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Silahkan pilih',
            });
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    @if(session('print_url'))
                                showCancelButton: true,
                        cancelButtonText: 'CETAK',
                    @endif
                    }).then((result) => {

                        @if(session('print_url'))
                            if (result.dismiss === Swal.DismissReason.cancel) {
                                window.open("{{ session('print_url') }}", "_blank");
                                return;
                            }
                        @endif

                        window.location.reload();
                    });
            @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        @endif
        });
    </script>

    <script>
        function togglePassword() {
            let passwordField = document.getElementById("password");
            let eyeIcon = document.getElementById("eye-icon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }


        function greatFormatRupiah(x) {
            var min = false;
            // Pastikan x memiliki nilai yang valid sebelum memanggil toString
            if (x === null || x === undefined) {
                x = "";
            }

            x = x.toString();
            if (x.includes("-")) {
                min = true;
            } else {
                min = false;
            }
            x = x.replace(/-/g, "");

            // decimal sekarang pakai koma
            var parts = x.toString().split(",");

            // hapus titik karena sekarang titik adalah ribuan
            parts[0] = parts[0].replace(/\./g, "");
            var bilangan = parts[0];

            if (parts[1] && parts[1] === "00") {
                parts.pop();
            }

            var number_string = bilangan.toString(),
                sisa = number_string.length % 3,
                rupiah = number_string.substr(0, sisa),
                ribuan = number_string.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                // ribuan sekarang pakai titik
                var separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            parts[0] = rupiah;

            if (min) {
                // gabung decimal pakai koma
                return "-" + parts.join(",");
            } else {
                return parts.join(",");
            }
        }

        function destroyFormatRupiah(x) {
            if (typeof x === "number") return x;
            if (!x) return 0;

            let strValue = String(x);
            let isNegative = strValue.includes("-");

            // hapus titik ribuan
            let cleaned = strValue.replace(/[^\d,]/g, "");

            // ubah koma decimal jadi titik
            let withoutDecimal = cleaned.replace(",", ".");

            let result = parseFloat(withoutDecimal) || 0;

            return isNegative ? -result : result;
        }
    </script>
</body>

</html>