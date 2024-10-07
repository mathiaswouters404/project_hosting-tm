@extends("layouts.template")

@section("title", "Account bewerken")

@section("main")

    <div class="container mb-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Wijzigen {{$user->firstName}} | <span class="font-weight-bold">Patiënt code: {{$user->id}}</span>
                    </div>

                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" id="upload-profile-picture" action="{{ url('uploadProfilePicture') }}" >
                            @csrf

                            <div class="row">
                                <div class="col-12 d-flex justify-content-center">
                                    <label for="profile_picture">
                                        <img id="profile-picture-img" style="height: 300px; width: 300px; border-radius: 50%;" src="/storage/images/{{ $user->profile_picture }}" alt="profile picture">
                                    </label>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input onchange="RegisterService.savePicture()" type="file" class="d-none" name="image" placeholder="Choose image" id="profile_picture">
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form id="edit-user-form" onsubmit="EditUserService.submitEditUser(event)" novalidate class="needs-validation" data-user-id="{{ $user->id }}">
                            @csrf

                            <div class="form-group row">
                                <label for="firstName"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Voornaam') }}</label>

                                <div class="col-md-6">
                                    <input id="firstName" type="text"
                                           class="form-control @error('firstName') is-invalid @enderror"
                                           name="firstName" value="{{ old('firstName',$user->firstName) }}" required
                                           autocomplete="firstName" autofocus>
                                    <div class="invalid-feedback">Vergeet uw voornaam niet in te vullen!</div>

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
                                           value="{{ old('lastName',$user->lastName) }}" required
                                           autocomplete="lastName" autofocus>
                                    <div class="invalid-feedback">Vergeet uw achternaam niet in te vullen!</div>

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
                                           value="{{ old('email',$user->email) }}" required autocomplete="email">
                                    <div class="invalid-feedback">Vul een geldig e-mailadres in!</div>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <button class="btn btn-primary">
                                        {{ __('Wachtwoord wijzigen') }}
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Opslaan') }}
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
        RegisterService.init("{{ csrf_token() }}")
    </script>
@endsection
