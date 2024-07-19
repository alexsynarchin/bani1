@extends('admin.base.base')
@section('content')
    <div class="container auth">
        <div class="row  justify-content-center mt-5">
            <div class="col-md-4">
                <div class="card text-center ">
                    <div class="card-header">
                        <h1 class="auth__title">
                            Вход
                        </h1>
                    </div>
                    <div class="card-body">
                        <login-form

                        >
                        </login-form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

