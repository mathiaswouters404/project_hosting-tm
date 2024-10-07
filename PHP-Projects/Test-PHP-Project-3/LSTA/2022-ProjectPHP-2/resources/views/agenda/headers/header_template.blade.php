<header class="menu bg-dark text-white">
    <h1 class="text-white">Agenda <span class="float-right"><i class="fa-solid fa-circle-question text-secondary" onclick="UserGuideService.show()"></i></span></h1>

    <section class="menu__profile mb-3">
        <div>
            <div class="menu__profile__icon">
                <img src="/storage/images/{{ $patient->profile_picture }}" alt="profile picture">
            </div>
            <div class="pt-3">
                @yield("firstname")
            </div>
        </div>
    </section>

    @yield("manage-section")

    <section class="menu__navigation menu__navigation--event">
        <ul>
            @yield("create-event")

            @yield("create-medication")

            @yield("create-logs")
        </ul>
    </section>

    @yield("legend")

    @yield("logout")

</header>
