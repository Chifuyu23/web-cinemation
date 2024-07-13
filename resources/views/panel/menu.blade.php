<a href="{{ route('dashboard') }}"
  @class([
    'mt-5 w-full rounded-lg text-base px-4 py-3',
    'bg-develobe-500 bg-opacity-10 text-develobe-500' => request()->routeIs('dashboard'),
    'hover:text-develobe-500 text-black duration-200' => !request()->routeIs('dashboard'),
    ])>
  Dashboard
</a>

<a href="{{ route('movies.index') }}"
  @class([
    'w-full rounded-lg text-base px-4 py-3',
    'bg-develobe-500 bg-opacity-10 text-develobe-500' => request()->routeIs('movies.index'),
    'hover:text-develobe-500 text-black duration-200' => !request()->routeIs('movies.index'),
    ])>
  Data Movies
</a>

<a href="{{ route('tv_shows.index') }}"
  @class([
    'w-full rounded-lg text-base px-4 py-3',
    'bg-develobe-500 bg-opacity-10 text-develobe-500' => request()->routeIs('tv_shows.index'),
    'hover:text-develobe-500 text-black duration-200' => !request()->routeIs('tv_shows.index'),
    ])>
  Data TV Shows
</a>

<a href="{{ route('settings') }}"
  @class([
    'w-full rounded-lg text-base px-4 py-3',
    'bg-develobe-500 bg-opacity-10 text-develobe-500' => request()->routeIs('settings'),
    'hover:text-develobe-500 text-black duration-200' => !request()->routeIs('settings'),
    ])>
  Pengaturan Data Profil
</a>

<a href="{{ route('password') }}"
  @class([
    'w-full rounded-lg text-base px-4 py-3',
    'bg-develobe-500 bg-opacity-10 text-develobe-500' => request()->routeIs('password'),
    'hover:text-develobe-500 text-black duration-200' => !request()->routeIs('password'),
    ])>
  Pengaturan Kata Sandi
</a>

<form method="POST" action="{{ route('logout') }}" class="p-0 m-0">
  @csrf

  <button type="submit"
    @class([
      'w-full rounded-lg text-base px-4 py-3 text-left',
      'bg-develobe-500 bg-opacity-10 text-develobe-500' => request()->routeIs('#'),
      'hover:text-develobe-500 text-black duration-200' => !request()->routeIs('#'),
      ])>
    Keluar
  </button>
</form>