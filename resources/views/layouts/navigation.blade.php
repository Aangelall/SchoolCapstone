<!-- Sidebar Navigation -->
<div x-data="{
    open: localStorage.getItem('sidebarOpen') === 'true',
    mobileMenuOpen: false,
    userManagementDropdown: false,
    masterListDropdown: false,
    init() {
        if (localStorage.getItem('sidebarOpen') === null) {
            this.open = false;
            localStorage.setItem('sidebarOpen', 'false');
        }
        this.$watch('open', value => {
            localStorage.setItem('sidebarOpen', value);
        });

        const checkWidth = () => {
            if (window.innerWidth <= 768) {
                this.open = false;
                localStorage.setItem('sidebarOpen', 'false');
            }
        };

        checkWidth();
        window.addEventListener('resize', checkWidth);
    }
}" :class="{ 'open': open }" class="sidebar">
    <!-- Menu button -->
    <div class="menu-btn" :class="{ 'open': open }">
        <i class='bx bx-menu' @click="open = !open"></i>
    </div>

    <ul class="nav-list">
        @auth
            @if(Auth::user()->role !== 'admin')
                <li class="profile-image-container">
                    <div class="profile-header">
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset(Auth::user()->profile_image) }}" alt="Profile Image" class="profile-image">
                        @else
                            <div class="profile-image profile-initial">
                                {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="profile-name">{{ Auth::user()->name }}</div>
                    </div>
                </li>
            @endif

            @if(Auth::user()->role === 'student')
                <!-- Student Navigation -->
                <li>
                    <a href="{{ route('user.dashboard') }}"
                       @click="$nextTick(() => { localStorage.setItem('sidebarOpen', open) })"
                       class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <i class='bx bx-grid-alt'></i>
                        <span class="links_name" x-cloak x-show="open">Dashboard</span>
                    </a>
                    <span class="tooltip" x-show="!open">Dashboard</span>
                </li>

                <li>
                    <a href="{{ route('student.grades') }}"
                       @click="$nextTick(() => { localStorage.setItem('sidebarOpen', open) })"
                       class="{{ request()->routeIs('student.grades') ? 'active' : '' }}">
                        <i class='bx bx-book-content'></i>
                        <span class="links_name" x-cloak x-show="open">My Grades</span>
                    </a>
                    <span class="tooltip" x-show="!open">My Grades</span>
                </li>

            @elseif(Auth::user()->role === 'teacher')
                <!-- Teacher Navigation -->
                <li>
                    <a href="{{ route('user.dashboard') }}"
                       @click="$nextTick(() => { localStorage.setItem('sidebarOpen', open) })"
                       class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <i class='bx bx-grid-alt'></i>
                        <span class="links_name" x-cloak x-show="open">Dashboard</span>
                    </a>
                    <span class="tooltip" x-show="!open">Dashboard</span>
                </li>

                <li>
                    <a href="{{ route('advisory.class') }}"
                       @click="$nextTick(() => { localStorage.setItem('sidebarOpen', open) })"
                       class="{{ request()->routeIs('advisory.class') ? 'active' : '' }}">
                        <i class='bx bx-book-reader'></i>
                        <span class="links_name" x-cloak x-show="open">Advisory Class</span>
                    </a>
                    <span class="tooltip" x-show="!open">Advisory Class</span>
                </li>

                <li>
                    <a href="{{ route('subjects') }}" class="{{ request()->routeIs('subjects') ? 'active' : '' }}">
                        <i class='bx bx-book'></i>
                        <span class="links_name" x-cloak x-show="open">Subjects</span>
                    </a>
                    <span class="tooltip" x-show="!open">Subjects</span>
                </li>

                <li>
                    <a href="{{ route('achievers') }}"
                       @click="$nextTick(() => { localStorage.setItem('sidebarOpen', open) })"
                       class="{{ request()->routeIs('achievers') ? 'active' : '' }}">
                        <i class='bx bx-trophy'></i>
                        <span class="links_name" x-cloak x-show="open">Achievers</span>
                    </a>
                    <span class="tooltip" x-show="!open">Achievers</span>
                </li>

            @elseif(Auth::user()->role === 'admin')
                <!-- Admin Navigation -->
                <li>
                    <a href="{{ route('dashboard') }}"
                       @click="$nextTick(() => { localStorage.setItem('sidebarOpen', open) })"
                       class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class='bx bx-grid-alt'></i>
                        <span class="links_name" x-cloak x-show="open">Dashboard</span>
                    </a>
                    <span class="tooltip" x-show="!open">Dashboard</span>
                </li>

                <li>
                    <a href="#"
                       @click.prevent="userManagementDropdown = !userManagementDropdown"
                       class="{{ request()->routeIs('add.teacher') || request()->routeIs('add.student') ? 'active' : '' }}">
                        <i class='bx bx-user'></i>
                        <span class="links_name" x-cloak x-show="open">User Management</span>
                        <i class='bx bx-chevron-down dropdown-icon' x-cloak x-show="open"></i>
                    </a>
                    <span class="tooltip" x-show="!open">User Management</span>
                </li>

                <div x-cloak x-show="userManagementDropdown && open" class="nested-dropdown">
                    <li class="nested-item">
                        <a href="{{ route('add.teacher') }}" class="nested-link {{ request()->routeIs('add.teacher') ? 'active' : '' }}">
                            <i class='bx bx-user-plus'></i>
                            <span class="links_name" x-cloak x-show="open">Manage Teachers</span>
                        </a>
                        <span class="tooltip" x-show="!open">Manage Teachers</span>
                    </li>
                    <li class="nested-item">
                        <a href="{{ route('add.student') }}" class="nested-link {{ request()->routeIs('add.student') ? 'active' : '' }}">
                            <i class='bx bx-user-plus'></i>
                            <span class="links_name" x-cloak x-show="open">Manage Students</span>
                        </a>
                        <span class="tooltip" x-show="!open">Manage Students</span>
                    </li>
                </div>

                <li>
                    <a href="{{ route('master.list') }}"
                       @click="$nextTick(() => { localStorage.setItem('sidebarOpen', open) })"
                       class="{{ request()->routeIs('master.list') ? 'active' : '' }}">
                        <i class='bx bx-list-ul'></i>
                        <span class="links_name" x-cloak x-show="open">Master List</span>
                    </a>
                    <span class="tooltip" x-show="!open">Master List</span>
                </li>

                <li>
                    <a href="{{ route('section.subject') }}"
                       @click="$nextTick(() => { localStorage.setItem('sidebarOpen', open) })"
                       class="{{ request()->routeIs('section.subject') ? 'active' : '' }}">
                        <i class='bx bx-book-content'></i>
                        <span class="links_name" x-cloak x-show="open">Sections & Subjects</span>
                    </a>
                    <span class="tooltip" x-show="!open">Sections & Subjects</span>
                </li>
            @endif
        @endauth
    </ul>

    <!-- Profile Section -->
    <div class="profile" :class="{ 'open': open }">
        <div class="profile-details">
            <div class="name_job">
                <div class="name" x-cloak x-show="open">{{ Auth::user()->name }}</div>
                <div class="job" x-cloak x-show="open">{{ Auth::user()->role }}</div>
            </div>
        </div>
        <!-- <form method="POST" action="{{ route('logout') }}" class="logout-form" x-cloak x-show="open">
            @csrf
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();">
                <i class='bx bx-log-out' id="log_out"></i>
            </a>
        </form> -->
        <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="dropdown-item text-red-600">
        <i class='bx bx-log-out'></i>
        <span>Logout</span>
    </button>
</form>
        <div class="mobile-logout" x-show="!open">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); this.closest('form').submit();"
                   class="tooltip-container">
                    <i class='bx bx-log-out' id="log_out_mobile"></i>
                    <span class="tooltip">Logout</span>
                </a>
            </form>
        </div>
    </div>


<!-- Top Navigation Bar -->
<nav class="top-navbar" :class="{ 'sidebar-open': open }">
    <div class="navbar-content">
        <div class="left-section">
            <button @click="open = !open"
                    class="mobile-menu-button"
                    type="button">
                <i class='bx bx-menu'></i>
            </button>

            <div class="logo-container">
                <a href="{{ Auth::user()->role === 'admin' ? route('dashboard') : route('user.dashboard') }}" class="navbar-logo-link">
                    <img src="{{ asset('img/LOGO.png') }}" alt="Application Logo" class="navbar-logo">
                    <span class="navbar-title">Magallanes National High School</span>
                </a>
            </div>
        </div>

        <div class="right-section">
            <div x-data="{ show: false }"
                 class="user-menu">
                <button @click="show = !show"
                        class="user-menu-btn"
                        type="button">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <i class='bx bx-chevron-down' :class="{ 'transform rotate-180': show }"></i>
                </button>
                <div x-show="show"
                     x-cloak
                     @click.away="show = false"
                     @keydown.escape.window="show = false"
                     class="user-dropdown">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class='bx bx-user'></i>
                        <span>Profile</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-red-600">
                            <i class='bx bx-log-out'></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
</div>

<!-- Add Boxicons CSS -->
<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

<style>
[x-cloak] { display: none !important; }

.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    height: 100%;
    width: 78px;
    background: #3c8d50;
    padding: 6px 14px;
    z-index: 99;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.sidebar.open {
    width: 250px;
}

.menu-btn {
    position: absolute;
    top: 6px;
    left: 50%;
    transform: translateX(-50%);
    padding: 6px;
    display: flex;
    justify-content: center;
    width: 50px;
    transition: all 0.3s ease;
    z-index: 100;
}

.menu-btn.open {
    left: calc(100% - 30px);
    transform: translateX(-50%);
}

.menu-btn i {
    color: #fff;
    font-size: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sidebar.open .menu-btn i {
    transform: rotate(180deg);
}

.sidebar .nav-list {
    margin-top: 60px;
    flex-grow: 1;
    padding: 0;
    margin-bottom: 60px;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

.sidebar li {
    position: relative;
    margin: 8px 0;
    list-style: none;
    width: 100%;
    display: flex;
    justify-content: center;
}

.profile-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

.profile-name {
    color: #fff;
    font-size: 20px;
    margin-top: 8px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
    display: none;
}

.sidebar.open .profile-name {
    display: block;
}

.profile .name,
.profile .job {
    display: none;
}

.sidebar.open .profile .name,
.sidebar.open .profile .job {
    display: block;
}

.sidebar li .tooltip {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: 122px;
    z-index: 3;
    background: #fff;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 15px;
    font-weight: 400;
    opacity: 0;
    white-space: nowrap;
    pointer-events: none;
    transition: 0s;
    color: #333;
}

.sidebar li:hover .tooltip {
    opacity: 1;
    pointer-events: auto;
    transition: all 0.4s ease;
    top: 50%;
    transform: translateY(-50%);
}

.sidebar.open li .tooltip {
    display: none;
}

.sidebar li a {
    display: flex;
    height: 100%;
    width: 50px;
    border-radius: 12px;
    align-items: center;
    text-decoration: none;
    background: #3c8d50;
    position: relative;
    transition: all 0.3s ease;
    padding: 12px;
    justify-content: center;
}

.sidebar.open li a {
    width: 100%;
    justify-content: flex-start;
}

.nested-dropdown {
    width: 100%;
    padding-left: 20px;
}

.nested-item {
    margin: 5px 0 !important;
}

.nested-link {
    background: #2a6438 !important;
}

.sidebar.open .nested-link {
    width: 95% !important;
}

.sidebar li a:hover,
.sidebar li a.active {
    background: #fff;
}

.sidebar li a i {
    height: 35px;
    min-width: 35px;
    border-radius: 12px;
    line-height: 35px;
    text-align: center;
    color: #fff;
    font-size: 18px;
    transition: all 0.3s ease;
}

.sidebar li a:hover i,
.sidebar li a.active i {
    color: #3c8d50;
}

.sidebar li a .links_name {
    color: #fff;
    font-size: 15px;
    font-weight: 400;
    white-space: nowrap;
    pointer-events: none;
    transition: 0.3s;
    margin-left: 10px;
}

.sidebar li a:hover .links_name,
.sidebar li a.active .links_name {
    color: #3c8d50;
}

.dropdown-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
}

.profile {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 78px;
    background: #2d6b3c;
    padding: 10px 14px;
    transition: all 0.3s ease;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.profile-image-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 0;
    margin-bottom: 20px;
    width: 100%;
}

.profile-image {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    transition: all 0.3s ease;
}

.profile-initial {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #2d6b3c;
    color: white;
    font-size: 20px;
    font-weight: bold;
}

.sidebar.open .profile-image {
    width: 100px;
    height: 100px;
}

.sidebar.open .profile-initial {
    width: 100px;
    height: 100px;
    font-size: 36px;
}

.sidebar.open .profile {
    width: 250px;
}

@media (max-width: 768px) {
    .sidebar:not(.open) .profile {
        width: 0;
        padding: 0;
        visibility: hidden;
    }
}

.profile-details {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-grow: 1;
}

.profile .name,
.profile .job {
    font-size: 15px;
    font-weight: 400;
    color: #fff;
    white-space: nowrap;
    text-align: center;
}

.profile .job {
    font-size: 12px;
}

.logout-form {
    display: flex;
    justify-content: center;
    width: 50px;
}

.mobile-logout {
    display: flex;
    justify-content: center;
    width: 50px;
    position: relative;
}

.mobile-logout .tooltip-container {
    position: relative;
    display: inline-block;
}

.mobile-logout .tooltip {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: 60px;
    z-index: 3;
    background: #fff;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 15px;
    font-weight: 400;
    opacity: 0;
    white-space: nowrap;
    pointer-events: none;
    transition: 0s;
    color: #333;
}

.mobile-logout .tooltip-container:hover .tooltip {
    opacity: 1;
    pointer-events: auto;
    transition: all 0.4s ease;
}

.profile #log_out,
.profile #log_out_mobile {
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    height: 35px;
    min-width: 35px;
    border-radius: 12px;
    line-height: 35px;
    text-align: center;
}

.home-section {
    position: relative;
    background: #e6f0f9;
    min-height: 100vh;
    top: 0;
    left: 78px;
    width: calc(100% - 78px);
    transition: all 0.3s ease;
    z-index: 2;
    margin-top: 64px;
}

.sidebar.open ~ .home-section {
    left: 250px;
    width: calc(100% - 250px);
}

.top-navbar {
    position: fixed;
    top: 0;
    right: 0;
    left: 78px;
    height: 64px;
    background: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    z-index: 98;
}

.top-navbar.sidebar-open {
    left: 250px;
}

.navbar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
    padding: 0 24px;
}

.left-section {
    display: flex;
    align-items: center;
}

.logo-container {
    display: flex;
    align-items: center;
}

.navbar-logo-link {
    display: flex;
    align-items: center;
    text-decoration: none;
}

.navbar-logo {
    height: 40px;
    width: 40px;
    object-fit: contain;
}

.navbar-title {
    color: #3c8d50;
    font-size: 18px;
    font-weight: 600;
    margin-left: 10px;
}

.right-section {
    display: flex;
    align-items: center;
    gap: 16px;
}

.mobile-menu-button {
    display: none;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #3c8d50;
}

.user-menu {
    position: relative;
}

.user-menu-btn {
    background: none;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.user-menu-btn:hover {
    background-color: #f3f4f6;
}

.user-menu-btn i {
    transition: transform 0.2s ease;
}

.user-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    width: 200px;
    z-index: 1000;
    border: 1px solid #e5e7eb;
}

.user-dropdown .dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    width: 100%;
    border: none;
    background: none;
    cursor: pointer;
    text-align: left;
    transition: background-color 0.2s;
    color: #1a1a1a;
    text-decoration: none;
    margin: 0;
}

.user-dropdown .dropdown-item.text-red-600 {
    color: #dc2626;
}

.user-dropdown .dropdown-item.text-red-600:hover {
    background-color: #fef2f2;
}

.user-dropdown .dropdown-item span {
    color: inherit;
}

.user-name {
    font-weight: 500;
    color: #3c8d50;
}

@media (max-width: 768px) {
    .sidebar {
        width: 0 !important;
        padding: 6px 0;
        overflow: hidden;
    }

    .sidebar.open {
        width: 250px !important;
        padding: 6px 14px;
    }

    .menu-btn {
        display: none;
    }

    .home-section {
        left: 0 !important;
        width: 100% !important;
    }

    .sidebar.open ~ .home-section {
        left: 0 !important;
        width: 100% !important;
        transform: translateX(250px);
    }

    .top-navbar {
        left: 0 !important;
        width: 100%;
    }

    .top-navbar.sidebar-open {
        left: 0 !important;
        width: 100%;
        transform: translateX(250px);
    }

    .navbar-content {
        padding: 0 12px;
    }

    .mobile-menu-button {
        display: block;
        margin-right: 12px;
    }

    .navbar-title {
        font-size: 14px;
    }

    .navbar-logo {
        height: 32px;
        width: 32px;
    }

    .sidebar.open::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: -1;
    }
}
</style>
