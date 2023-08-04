@extends('auth.layouts.app')

@section('content')
<style type="text/css">
    .error:after {
        display: block;
        content: "";
        margin-bottom: 0.5rem;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (\Session::has('success'))
            <div class="alert alert-success">
                {!! \Session::get('success') !!}
            </div>
            @endif

            @if (\Session::has('warning'))
            <div class="alert alert-warning">
                {!! \Session::get('warning') !!}
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    {{ __('Reset Password') }}
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('password.update', $email) }}" id="loginForm1">
                        @method('PUT')
                        @csrf

                        <input type="hidden" name="token" value="{{ $resetToken }}">

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input id="password" type="password" class="form-control toggle-password"
                                            name="password" autocomplete>
                                        <div class="input-group-btn">
                                            <div class="input-group-append" style="height: 2.6em;">
                                                <span class="input-group-text" id="togglePassword"><i id="eye_pwd"
                                                        class="fa fa-fw fa-eye field_icon"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <label for="password" generated="true" id="password_error"
                                        class="error password_error"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm
                                Password') }}</label>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input id="password-confirm" type="password"
                                            class="form-control toggle-password" name="password_confirmation"
                                            autocomplete>
                                        <div class="input-group-btn">
                                            <div class="input-group-append" style="height: 2.6em;">
                                                <span class="input-group-text" id="toggleConfirmPassword"><i
                                                        id="eye_confirm" class="fa fa-fw fa-eye field_icon"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <label for="password-confirm" generated="true" class="error"></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after_scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
<script type="text/javascript">
    $(document).ready(function() {
                if ($(".password_error label").css("display") === 'none') {
                   alert(1);
                }
                /*$.validator.addMethod("pwcheck", function(value) {
                   return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
                       && /[a-z]/.test(value) // has a lowercase letter
                       && /\d/.test(value) // has a digit
                });*/

                $.validator.addMethod("pwcheck", function(value) {
                    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/.test(value)
                });


                $("#loginForm1").validate({
                    rules: {
                        password: {
                            required: true,
                            minlength: 8,
                            maxlength: 16
                            //pwcheck: true,
                        },
                        password_confirmation: {
                            equalTo: "#password"
                        },

                    },
                    messages: {
                        password: {
                            required: "Enter Password",
                            minlength: "Please Enter minimum 8 Characters",
                            maxlength: "Please Enter maximum 16 Characters"
                        },
                        password_confirmation: " Enter Confirm Password Same as New Password",
                    }
                });
                const togglePassword = document.querySelector('#togglePassword');
                const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
                const password = document.querySelector('#password');
                const confirm_password = document.querySelector('#password-confirm');

                togglePassword.addEventListener('click', function(e) {
                    // toggle the type attribute
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';

                    password.setAttribute('type', type);
                     $('#eye_pwd').toggleClass('fa fa-fw fa-eye');
                     $('#eye_pwd').toggleClass('fa fa-fw fa-eye-slash');
                });
                toggleConfirmPassword.addEventListener('click', function(e) {
                    // toggle the type attribute

                    const confirm_type = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirm_password.setAttribute('type', confirm_type);
                   $('#eye_confirm').toggleClass('fa fa-fw fa-eye');
                     $('#eye_confirm').toggleClass('fa fa-fw fa-eye-slash');
                });
            });
</script>
@endpush
@endsection