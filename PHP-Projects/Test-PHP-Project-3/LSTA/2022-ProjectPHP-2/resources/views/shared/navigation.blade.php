<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand text-secondary" href="/"><img src="/img/logo.png" alt="logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04"
                aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav mr-auto">
                @if( auth()->check() && (Auth::user()->role()->first()->name == "Patient"))
                    <li class="nav-item">
                        <a class="nav-link" href="/log">Logboek<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/prescription">Medicatie</a>
                    </li>
                @endif
                @if(auth()->check() &&  (Auth::user()->role()->first()->name == "Dokter"))
                        <li class="nav-item">
                            <a class="nav-link" href="/medication">Medicatie</a>
                        </li>
                @endif
                @if(auth()->check() && (Auth::user()->role()->first()->name == "Mantelzorger" || Auth::user()->role()->first()->name == "Dokter")  && (@isset($patient)))
                    <li class="nav-item">
                        <a class="nav-link" href="#">{{$patient->firstName}} {{$patient->lastName}}</a>
                    </li>
                    @if(Auth::user()->role()->first()->name == "Dokter")
                        <li class="nav-item">
                            <a class="nav-link" href="/{{$patient->id}}/prescription"><i
                                    class="fa-solid fa-circle-info"></i> Dossier</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="/agenda/{{$patient->id}}"><i class="fa-solid fa-calendar"></i> Agenda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/log/{{$patient->id}}?searchLogs={{$patient->firstName." ".$patient->lastName}}"><i class="fa-solid fa-book"></i> Logboek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""><i class="fa-solid fa-circle-question"></i> Antwoorden</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="window.Patients.questionnaire({{$patient->id}})"><i
                                class="fa-solid fa-circle-question"></i> Nieuwe Vragenlijst</a>
                    </li>
                    @if(Auth::user()->role()->first()->name == "Mantelzorger")
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                               onclick="window.Patients.rights({{$patient->id}},'{{$patient->firstName}} {{$patient->lastName}}')"><i
                                    class="fa-solid fa-shield"></i> Rechten</a>
                        </li>
                    @endif
                @endif

            </ul>
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Inloggen') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Registreren') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->firstName . ' ' . Auth::user()->lastName }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right bg-dark" aria-labelledby="navbarDropdown">
                            <a href="/auth/edit" class="dropdown-item">Account</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Uitloggen') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
