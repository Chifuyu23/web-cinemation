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
            <form id="dataForm" method="POST" action="{{ route('movies.create') }}" enctype="multipart/form-data" class="flex flex-col">
              @csrf
              <div class="font-semibold text-3xl">Tambah Data</div>

              @if($errors->any())
                <div class="bg-red-700 text-white w-full p-4 rounded-md my-4">
                  <strong>Whoops!</strong> Telah terjadi kesalahan dari input yang Anda berikan<br><br>
                  <ul class="list-disc mx-2">
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <div class="font-semibold text-2xl mt-7">Judul <span class="text-red-500">*</span></div>
              <div class="w-full border border-[rgba(0,0,0,0.5)] rounded-lg font-semibold text-xl p-4 mt-2">
                <input type="text" name="title" maxlength="255" class="outline-none w-full font-normal" value="{{ old('title') }}" autocomplete="off" required/>
              </div>

              <div class="font-semibold text-2xl mt-7">Deskripsi <span class="text-red-500">*</span></div>
              <div class="w-full border border-[rgba(0,0,0,0.5)] rounded-lg font-semibold text-xl p-4 mt-2">
                <textarea name="overview" rows="5" class="outline-none w-full font-normal resize-none" required>{{ old('overview') }}</textarea>
              </div>

              <div class="font-semibold text-2xl mt-7">Tanggal Rilis <span class="text-red-500">*</span></div>
              <div class="w-full border border-[rgba(0,0,0,0.5)] rounded-lg font-semibold text-xl p-4 mt-2">
                <input id="datePicker" type="date" name="release_date" class="outline-none w-full font-normal bg-white" value="{{ old('release_date') }}" onclick="this.showPicker()" required/>
              </div>

              <div class="font-semibold text-2xl mt-7">Rating (1-10) <span class="text-red-500">*</span></div>
              <div class="w-full border border-[rgba(0,0,0,0.5)] rounded-lg font-semibold text-xl p-4 mt-2">
                <input type="number" name="vote_average" class="outline-none w-full font-normal" value="{{ old('vote_average') }}" autocomplete="off"
                  max="10"
                  min="1"
                  required/>
              </div>

              <div class="font-semibold text-2xl mt-7">Durasi (dalam menit) <span class="text-red-500">*</span></div>
              <div class="w-full border border-[rgba(0,0,0,0.5)] rounded-lg font-semibold text-xl p-4 mt-2">
                <input type="number" name="runtime" class="outline-none w-full font-normal" value="{{ old('runtime') }}" autocomplete="off"
                  min="1"
                  required/>
              </div>

              <div class="font-semibold text-2xl mt-7">Trailer (Youtube ID) <span class="text-red-500">*</span></div>
              <div class="w-full border border-[rgba(0,0,0,0.5)] rounded-lg font-semibold text-xl p-4 mt-2">
                <input type="text" name="youtube_id" maxlength="255" class="outline-none w-full font-normal" value="{{ old('youtube_id') }}" autocomplete="off" required/>
              </div>

              <div class="font-semibold text-2xl mt-7">Tipe <span class="text-red-500">*</span></div>
              <div class="w-full border border-[rgba(0,0,0,0.5)] rounded-lg font-semibold text-xl p-4 mt-2">
                <select class="pr-2 py-2 w-full" name="type">
                  <option value="movie">Movie</option>
                  <option value="tv_show">TV Show</option>
                </select>
              </div>

              <div class="font-semibold text-2xl mt-7">File Backdrop (Max {{ env('MAX_FILE_SIZE') }} MB) <span class="text-red-500">*</span></div>
              <div class="flex items-center justify-left w-full mt-2">
                <input id="backdrop_file" type="file" name="backdrop_file" accept="image/png, image/jpeg, image/jpg" value="{{ old('backdrop_file') }}" onchange="validateSize(this, {{ env('MAX_FILE_SIZE') }}, '#backdrop_file')" required/>
              </div>
              <x-input-error :messages="$errors->get('backdrop_file')" class="mt-2" />

              <div class="font-semibold text-2xl mt-7">File Poster (Max {{ env('MAX_FILE_SIZE') }} MB) <span class="text-red-500">*</span></div>
              <div class="flex items-center justify-left w-full mt-2">
                <input id="poster_file" type="file" name="poster_file" accept="image/png, image/jpeg, image/jpg" value="{{ old('poster_file') }}" onchange="validateSize(this, {{ env('MAX_FILE_SIZE') }}, '#poster_file')" required/>
              </div>
              <x-input-error :messages="$errors->get('poster_file')" class="mt-2" />

              <div class="flex flex-row w-full items-center justify-center gap-x-4 mt-10">
                <button id="submitButton" type="submit" class="w-fit bg-develobe-500 py-2 px-3 rounded-lg text-white font-semibold hover:drop-shadow-lg duration-200 flex flex-row items-center justify-center">
                  <svg id="loadingIndicator" class="hidden animate-spin mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Tambah Data
                </button>

                <a id="cancelButton" href="{{ route('movies.index') }}" class="w-fit bg-red-500 py-2 px-3 rounded-lg text-white font-semibold hover:drop-shadow-lg duration-200">Kembali</a>
              </div>
            </form>
          </div>
        </div>

        <!-- Footer Section -->
        @include('footer')
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    <script>
      function validateSize(input, maxSize, id){
        const fileSize = input.files[0].size / 1024 / 1024; // in MB
        if (fileSize > maxSize){
          alert(`File melebihi limit ${maxSize} MB`);
          $(id).val('');
        }
      }

      $('#dataForm').on('submit', function(){
        $('#submitButton').attr('disabled', 'true');
        $('#cancelButton').attr('disabled', 'true');
        $('#submitButton').addClass('cursor-not-allowed bg-opacity-50');
        $('#cancelButton').addClass('cursor-not-allowed bg-opacity-50 pointer-events-none');
        $('#loadingIndicator').removeClass('hidden');
      });
    </script>
  </body>
</html>