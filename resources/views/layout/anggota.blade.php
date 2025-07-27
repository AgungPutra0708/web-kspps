<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Web Anggota</title>
    @include('layout.header')
    <link rel="icon" href="{{ asset('assets/img/logo-koperasi.png') }}" type="image/x-icon">
    <link href="{{ asset('assets/css/style-anggota.css') }}" rel="stylesheet" />
</head>

<body>
    <!-- Page Wrapper -->
    <div id="wrapper-anggota" onload="stopLoading()">
        <!-- Loading Spinner -->
        <div id="loader" class="d-flex justify-content-center align-items-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Add this to properly handle the content load -->
            <div id="main-content">
                {{-- Content loaded dynamically will go here --}}
            </div>
        </div>
        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Keluar</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Yakin akan keluar?
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Tidak</button>
                            <button type="submit" class="btn btn-danger">Keluar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Profile Modal-->
        <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('changePassword') }}" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ganti Password</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="new_password">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-user" name="new_password"
                                        id="password" placeholder="Enter Password">
                                    <div class="input-group-append">
                                        <span class="input-group-text border-0" onclick="togglePassword()"
                                            style="cursor: pointer;">
                                            <i id="eye-icon" class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Tidak</button>
                            <button type="submit" class="btn btn-primary">Ganti</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @include('anggota.nav')
    </div>
    <!-- End of Page Wrapper -->
    <!-- End of Content Wrapper -->
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
        // Fungsi untuk menampilkan spinner
        function setLoading() {
            $('#loader').removeClass('hide-loader'); // Show the loader
        }
        // Fungsi untuk menyembunyikan spinner
        function stopLoading() {
            $('#loader').addClass('hide-loader'); // Hide the loader
        }
        $(document).ready(function() {
            // Fungsi untuk menampilkan spinner
            function setLoading() {
                $('#loader').removeClass('hide-loader'); // Show the loader
            }
            // Fungsi untuk menyembunyikan spinner
            function stopLoading() {
                $('#loader').addClass('hide-loader'); // Hide the loader
            }
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK'
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
            // Sembunyikan loader pada awal load
            stopLoading();
            // Load content of the active link on page load
            var activeLink = $('.list.active a').attr('href');
            if (activeLink) {
                loadContent(activeLink);
            }
            // Handle click events for navigation links
            $('.list a').on('click', function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                // Update the active class immediately
                $('.list').removeClass('active');
                $(this).parent().addClass('active');
                // Load the content via AJAX
                loadContent(url);
            });
            // Event delegation for pagination links
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault(); // Mencegah navigasi default
                var url = $(this).attr('href'); // Ambil URL dari link pagination
                console.log(url);
                // loadContent(url); // Panggil fungsi untuk memuat konten baru
            });
        });
        // Function to format a number as Rupiah (without "Rp" and using dots for thousands, commas for decimals)
        function formatRupiah(number) {
            return number.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }).replace(/,/g, ',').replace(/\./g, '.');
        }
        // Function to load content with AJAX
        function loadContent(url) {
            setLoading(); // Tampilkan loader sebelum konten dimuat
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#main-content').html(data);
                    stopLoading(); // Sembunyikan loader setelah konten dimuat
                },
                error: function(xhr) {
                    stopLoading(); // Sembunyikan loader jika terjadi error
                    if (xhr.status === 401) {
                        // Jika session expired (status 401), arahkan pengguna ke halaman login
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sesi Habis!',
                            text: 'Sesi Anda telah habis. Silakan login kembali.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '/login'; // Arahkan ke halaman login
                        });
                    } else {
                        // Tampilkan pesan error umum jika bukan masalah sesi
                        alert('Error loading content');
                    }
                }
            });
        }
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
    </script>
</body>

</html>
