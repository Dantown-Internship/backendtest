<nav class="navbar navbar-expand-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </li>
                <li class="nav-item">
                    <x-nav-link :href="route('users.logs')" :active="request()->routeIs('users.logs')">
                        {{ __('Logs') }}
                    </x-nav-link>
                </li>
            </ul>
@php
        $notifications = auth()->user()->unreadNotifications->sortByDesc('created_at');
@endphp
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fa fa-bell" aria-hidden="true"></i>
                        <span class="notification-badge">{{ count($notifications) }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li style="width:300px;height:350px; overflow-y:scroll" class="p-0">
                            @foreach($notifications as $notification)
                            <div class="card card-sm mb-1">
                                <div class="card-body">
                                  <h6 class="card-title">{{ ucfirst($notification->data['type']) }}</h6>
                                  <p class="card-text">{{ $notification->data['message'] }}</p>
                                  <a href="{{ route('notification.read',$notification->id) }}" class="btn btn-danger badge">Mark as Read</a>
                                </div>
                              </div>
                            @endforeach
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">{{ Auth::user()->name }}</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.notification-badge {
    position: absolute;
    top: 8px;
    right: 16px;
    padding: 2px 4px;
    border-radius: 50%;
    background: red;
    color: white;
    font-size: 10px;
}
</style>
