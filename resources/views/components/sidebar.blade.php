<!-- resources/views/components/sidebar.blade.php -->

<aside class="bg-gray-800 text-white w-64 min-h-screen">
    <div class="p-4">
        <h2 class="text-2xl font-semibold">Admin Panel</h2>
    </div>
    <nav class="mt-6">
        <ul>
            <li class="px-4 py-2 hover:bg-gray-700">
                <a href="{{ route('dashboard') }}" class="block">Dashboard</a>
            </li>
            @auth
            @if (Auth::user()->role === 'admin')
            <li class="px-4 py-2 hover:bg-gray-700">
                <a href="{{ route('user.management') }}" class="block">User Management</a>
            </li>
            @endif
            @endauth
        </ul>
    </nav>
</aside>
