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
          <div class="flex flex-col w-full ml-12 bg-white p-10 rounded-2xl drop-shadow-[0_0px_2px_rgba(0,0,0,0.2)] overflow-x-scroll">
            <div class="flex flex-row items-center">
              <div class="font-semibold text-3xl">Data TV Shows</div>
              <a href="{{ route('movies.form') }}" class="rounded-md bg-develobe-500 text-white px-4 py-2 w-fit ml-auto hover:drop-shadow-xl duration-300">Tambah data</a>
              <a href="{{ route('tv_shows.import_form') }}" class="rounded-md bg-orange-500 text-white px-4 py-2 w-fit ml-3 hover:drop-shadow-xl duration-300">Impor data</a>
            </div>

            <div class="overflow-x-scroll">
              <table class="table-auto w-full mt-5">
                <thead class="bg-slate-200">
                  <tr>
                    <th class="px-4 py-2 min-w-[50px] w-2/12">No</th>
                    <th class="px-4 py-2 min-w-[300px] w-2/12">Action</th>
                    <th class="px-4 py-2 min-w-[250px] w-2/12">Judul</th>
                    <th class="px-4 py-2 min-w-[150px] w-2/12">Rating</th>
                    <th class="px-4 py-2 min-w-[150px] w-2/12">Tanggal Rilis</th>
                    <th class="px-4 py-2 min-w-[150px] w-2/12">Durasi Movie</th>
                    <th class="px-4 py-2 min-w-[150px] w-2/12">Views</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($tvShows as $key => $item)

                    @php
                      $hour = (int)($item->runtime / 60);
                      $minute = $item->runtime % 60;
                      $duration = "{$hour}h {$minute}m";
                    @endphp

                    <tr class="border">
                      <td class="p-4">{{ $tvShows->firstItem() + $key }}</td>
                      <td class="p-4 flex flex-row items-center justify-center gap-2">
                        <a href="{{ route('movies.edit_form', ['id' => $item->id]) }}" class="bg-orange-500 text-white p-2 w-fit min-w-fit text-sm rounded-md cursor-pointer hover:drop-shadow-md duration-200">Edit</a>
                        <a href="#" class="bg-develobe-500 text-white p-2 w-fit min-w-fit text-sm rounded-md cursor-pointer hover:drop-shadow-md duration-200">Detail</a>
                        <a href="{{ route('movies.delete', ['id' => $item->id]) }}" class="bg-red-500 text-white p-2 w-fit min-w-fit text-sm rounded-md cursor-pointer hover:drop-shadow-md duration-200" onclick="return confirm('Apakah ingin menghapus data ini?')">Hapus</a>
                      </td>
                      <td class="p-4">{{ $item->title }}</td>
                      <td class="p-4">{{ $item->vote_average * 10 }}</td>
                      <td class="p-4">{{ $item->release_date }}</td>
                      <td class="p-4">{{ $duration }}</td>
                      <td class="p-4">{{ $item->views }}</td>
                    </tr>

                  @endforeach
                </tbody>
              </table>

              <div class="mt-4">{{ $tvShows->onEachSide(0)->links() }}</div>
            </div>

          </div>
        </div>

        <!-- Footer Section -->
        @include('footer')
      </div>
    </div>
  </body>
</html>