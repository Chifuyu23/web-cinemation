<html>
  <head>
    <title>CINEMATION | Better Movie Better Life</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body>
    <div class="flex bg-white">
      <div class="w-full h-auto min-h-screen flex flex-col font-inter z-10">
        <!-- Header Section -->
        @include('header')

        <div class="w-full flex flex-row mt-16 mb-20 px-10">
          <!-- Menu Section -->
          <div class="flex flex-col w-[400px] min-w-[400px] bg-white p-6 rounded-2xl drop-shadow-[0_0px_2px_rgba(0,0,0,0.2)] h-fit">
            @include('panel.menu')
          </div>

          <!-- Content Section -->
          <div class="flex flex-col w-full ml-12 bg-white p-10 rounded-2xl drop-shadow-[0_0px_2px_rgba(0,0,0,0.2)]">
            <div class="font-semibold text-3xl">Halo {{ $user->name }}</div>
            <div class="flex flex-col mt-7 gap-y-5">
              <div class="text-xl">Total Movies : {{ $totalMovies }}</div>
              <div class="text-xl">Total TV Shows : {{ $totalTVShows }}</div>
              <div class="text-xl">Total Pengunjung : {{ $totalViews }}</div>
            </div>
          </div>
        </div>

        <!-- Footer Section -->
        @include('footer')
      </div>
    </div>
  </body>
</html>