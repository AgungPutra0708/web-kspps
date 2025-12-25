<!-- Footer -->

@if (Route::currentRouteName() != 'login')
    <footer class="sticky-footer bg-white">

        <div class="container my-auto">

            <div class="copyright text-center my-auto">

                <span>Copyright &copy; 2024 - BMT Sarana Wiraswasta Muslim</span>

            </div>

        </div>

    </footer>

    <!-- End of Footer -->



    <!-- Scroll to Top Button-->

    <a class="scroll-to-top rounded" href="#page-top">

        <i class="fas fa-angle-up"></i>

    </a>



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

                        <button type="submit" class="btn btn-primary">Keluar</button>

                    </div>

                </div>

            </form>

        </div>

    </div>



    <!-- Profile Modal-->

    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">

        <div class="modal-dialog" role="document">

            <form action="{{ route('management_user.changePassword') }}" method="post">

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
@endif
