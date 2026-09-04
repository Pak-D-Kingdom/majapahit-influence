<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3">
    <div class="container-fluid p-0">
        <h5 class="mb-0 fw-bold">@yield('page_title', 'Portal KOL')</h5>
        
        <div class="d-flex align-items-center ms-auto">
            <span class="badge bg-indigo-subtle text-indigo border me-3">
                KOL: {{ auth()->user()->kolProfile->nickname ?? (auth()->user()->name ?? 'Influencer') }}
            </span>
            <div class="dropdown">
                <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-4 me-2 text-primary"></i>
                    <span class="fw-semibold">{{ auth()->user()->name ?? 'KOL' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="/kol/profile"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
