<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div>
            <a class="navbar-brand brand-logo" href="#">
                <img height="100%" width="50%" src="{{ asset('backend/assets/logo.jpg') }}" alt="Menu Card">
            </a>
            <a class="navbar-brand brand-logo-mini" href="#">
                <img height="100%" width="100%" src="{{ asset('frontend/images/admin_logo.svg') }}"
                    alt="youshopping">
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text" id="greeting">Good Morning, <span class="text-black fw-bold"></span></h1>
                </h1>
                <h3 class="welcome-sub-text">Your performance summary this week </h3>
            </li>
        </ul>
        {{-- Dropdown --}}
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="img-xs rounded-circle" src="{{ asset('backend/images/profile-avatar.png') }}"
                        alt="Profile image">
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                        <img class="img-xs rounded-circle" src="{{ asset('backend/images/profile-avatar.png') }}"
                            alt="Profile image">
                        <p class="mb-1 mt-3 font-weight-semibold"></p>
                        <p class="fw-light text-muted mb-0"></p>
                    </div>
                    <a class="dropdown-item" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left text-primary me-2"></i>
                        SignOut
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-bs-toggle="offcanvas">
            <i class="bi bi-list text-bold"></i>
        </button>
    </div>
</nav>


@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var now = new Date();
            var hour = now.getHours();
            var greetingText = "Good Morning";

            if (hour >= 12 && hour < 17) {
                greetingText = "Good Afternoon";
            } else if (hour >= 17 || hour < 4) {
                greetingText = "Good Evening";
            }

            document.getElementById('greeting').innerHTML = greetingText +
                ', <span class="text-black fw-bold"></span>';
        });
    </script>
@endpush
