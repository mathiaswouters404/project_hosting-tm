@extends('layouts.template')

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Registreren') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}" id="register-form">
                            @csrf

                            <div class="form-group row">
                                <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Rol') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control" name="role_id" id="role">
                                        @foreach($roles as $role)
                                            <option
                                                value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : ''}}>{{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="border-top border-bottom mb-4 pt-3" id="rights">
                                <div class="form-group row">
                                    <div class="col-md-4 col-form-label text-md-right pb-0">Rechten</div>
                                </div>

                                {{-- Build a responsive form with all the right types in the database --}}
                                @foreach($rightTypes as $rightType)
                                    <div class="form-group row">
                                        <div class="col-md-4 col-form-label d-flex align-items-center">
                                                <label class="w-100 text-md-right" for="rights__{{ $rightType->name }}">{{ $rightType->display_name }}</label>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center flex-row">
                                            <input type="checkbox" name="right_id_{{ $rightType->id }}" id="rights__{{ $rightType->name }}">
                                        </div>
                                        <label class="col-md-7 col-form-label" for="rights__{{ $rightType->name }}">{{ $rightType->description}}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group row">
                                <label for="firstName"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Voornaam') }}</label>

                                <div class="col-md-6">
                                    <input id="fistName" type="text"
                                           class="form-control @error('firstName') is-invalid @enderror"
                                           name="firstName" value="{{ old('firstName') }}" required
                                           autocomplete="firstName" autofocus>

                                    @error('firstName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lastName"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Achternaam') }}</label>

                                <div class="col-md-6">
                                    <input id="lastName" type="text"
                                           class="form-control @error('lastName') is-invalid @enderror" name="lastName"
                                           value="{{ old('lastName') }}" required autocomplete="lastName" autofocus>

                                    @error('lastName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-right">{{ __('E-Mailadres') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Wachtwoord') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Bevestig Wachtwoord') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Registreer') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_after')
    <script>
        RegisterService.init("{{ csrf_token() }}");

        showRights();
        $('#role').change(showRights);

        function showRights(){
            console.log("test");
            let right = $('#role').val();
            console.log(right);

            if(right != 1){
                $('#rights').hide();
            }else{
                $('#rights').show();
            }
        }

    </script>
@endsection
