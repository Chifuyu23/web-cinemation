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
            <form id="dataForm" method="POST" action="{{ route('movies.import') }}" enctype="multipart/form-data" class="flex flex-col">
              @csrf
              <div class="font-semibold text-3xl">Impor Data</div>

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

              <div class="font-semibold text-2xl mt-7">JSON Data <span class="text-red-500">*</span></div>
              <div class="w-full border border-[rgba(0,0,0,0.5)] rounded-lg font-semibold text-xl p-4 mt-2">
                <textarea name="json" rows="20" class="outline-none w-full font-normal resize-none" required>{{ old('json') }}</textarea>
              </div>

              <div class="flex flex-row w-full items-center justify-center gap-x-4 mt-10">
                <button id="submitButton" type="submit" class="w-fit bg-develobe-500 py-2 px-3 rounded-lg text-white font-semibold hover:drop-shadow-lg duration-200 flex flex-row items-center justify-center">
                  <svg id="loadingIndicator" class="hidden animate-spin mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Impor Data
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