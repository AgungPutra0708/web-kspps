@extends('layout.auth')

@section('login')
    <!-- Outer Row -->
    <div class="row justify-content-center">
        <div class="col-xl-6 col-md-6">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <img src="{{ $dataProfile && $dataProfile->logo_koperasi ? asset('storage/' . $dataProfile->logo_koperasi) : '' }}"
                                        alt="Preview Banner" class="text-center mb-1"
                                        style="max-width: 200px; max-height: 200px; display: {{ $dataProfile && $dataProfile->logo_koperasi ? '' : 'none' }}">
                                </div>
                                <form action="{{ route('login.authenticate') }}" method="POST" class="user">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="username"
                                            name="username" placeholder="Enter Username">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" name="password"
                                            id="password" placeholder="Enter Password">
                                    </div>
                                    <button type="submit" class="btn btn-secondary btn-user btn-block">
                                        Login
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
