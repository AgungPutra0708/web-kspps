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
            setLoading(); // Show the loader before content is loaded
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#main-content').html(data);
                    stopLoading(); // Hide the loader after content is loaded
                },
                error: function() {
                    alert('Error loading content');
                    stopLoading(); // Hide the loader in case of an error
                }
            });
        }
    </script>
</body>

</html>
