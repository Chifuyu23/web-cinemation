<html>
  <head>
    <title>CINEMATION | Better Movie Better Life</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
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
            <form id="dataForm" method="POST" action="{{ route('password.update') }}" class="flex flex-col">
              @csrf
              @method('PUT')
              <div class="font-semibold text-3xl">Pengaturan Kata Sandi</div>

              @if ($errors->any())
                <div class="bg-red-700 text-white w-full p-4 rounded-md my-4">
                  <strong>Whoops!</strong> Telah terjadi kesalahan dari input yang Anda berikan<br><br>
                  <ul class="list-disc mx-2">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <div class="font-semibold text-2xl mt-7">Kata Sandi Lama (Min 8 karakter)</div>
              <div class="w-full border border-[rgba(0,0,0,0.5)] rounded-lg font-semibold text-xl p-4 mt-2">
                <input type="password" name="current_password" class="outline-none w-full font-normal" autocomplete="current-password" placeholder="********" minlength="8" required/>
                <i class="fa-solid fa-eye-slash cursor-pointer absolute right-14 mt-1" id="currentPasswordIcon"></i>
              </div>
              <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />

              <div class="font-semibold text-2xl mt-7">Kata Sandi Baru (Min 8 karakter)</div>
              <div class="w-full border border-[rgba(0,0,0,0.5)] rounded-lg font-semibold text-xl p-4 mt-2">
                <input type="password" name="password" class="outline-none w-full font-normal" autocomplete="new-password" placeholder="********" minlength="8" required/>
                <i class="fa-solid fa-eye-slash cursor-pointer absolute right-14 mt-1" id="newPasswordIcon"></i>
              </div>
              <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />

              <div class="font-semibold text-2xl mt-7">Konfirmasi Kata Sandi (Min 8 karakter)</div>
              <div class="w-full border border-[rgba(0,0,0,0.5)] rounded-lg font-semibold text-xl p-4 mt-2">
                <input name="password_confirmation" type="password" class="outline-none w-full font-normal" autocomplete="new-password" placeholder="********" minlength="8" required/>
                <i class="fa-solid fa-eye-slash cursor-pointer absolute right-14 mt-1" id="confirmationPasswordIcon"></i>
              </div>
              <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />

              <div class="flex flex-row w-full items-center justify-center gap-x-4 mt-10">
                <button id="submitButton" type="submit" class="w-fit bg-develobe-500 py-2 px-3 rounded-lg text-white font-semibold hover:drop-shadow-lg duration-200 flex flex-row items-center justify-center">
                  <svg id="loadingIndicator" class="hidden animate-spin mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Ubah Data
                </button>
                <a id="cancelButton" href="{{ route('dashboard') }}" class="w-fit bg-red-500 py-2 px-3 rounded-lg text-white font-semibold hover:drop-shadow-lg duration-200">Kembali</a>
              </div>
            </form>
          </div>
        </div>

        @include('footer')
      </div>
    </div>

    <script>
      const currentPasswordIcon = document.querySelector("#currentPasswordIcon");
      const currentPasswordInput = document.querySelector("input[name='current_password']");

      currentPasswordIcon.addEventListener("click", function () {
        if (currentPasswordInput.getAttribute("type") === "password"){
          currentPasswordInput.setAttribute("type", "text");
          
          currentPasswordIcon.classList.remove("fa-eye-slash");
          currentPasswordIcon.classList.add("fa-eye");
        } else {
          currentPasswordInput.setAttribute("type", "password");
          
          currentPasswordIcon.classList.remove("fa-eye");
          currentPasswordIcon.classList.add("fa-eye-slash");
        }
      });

      const newPasswordIcon = document.querySelector("#newPasswordIcon");
      const newPasswordInput = document.querySelector("input[name='password']");

      newPasswordIcon.addEventListener("click", function () {
        if (newPasswordInput.getAttribute("type") === "password"){
          newPasswordInput.setAttribute("type", "text");
          
          newPasswordIcon.classList.remove("fa-eye-slash");
          newPasswordIcon.classList.add("fa-eye");
        } else {
          newPasswordInput.setAttribute("type", "password");
          
          newPasswordIcon.classList.remove("fa-eye");
          newPasswordIcon.classList.add("fa-eye-slash");
        }
      });

      const confirmationPasswordIcon = document.querySelector("#confirmationPasswordIcon");
      const confirmationPasswordInput = document.querySelector("input[name='password_confirmation']");

      confirmationPasswordIcon.addEventListener("click", function () {
        if (confirmationPasswordInput.getAttribute("type") === "password"){
          confirmationPasswordInput.setAttribute("type", "text");
          
          confirmationPasswordIcon.classList.remove("fa-eye-slash");
          confirmationPasswordIcon.classList.add("fa-eye");
        } else {
          confirmationPasswordInput.setAttribute("type", "password");
          
          confirmationPasswordIcon.classList.remove("fa-eye");
          confirmationPasswordIcon.classList.add("fa-eye-slash");
        }
      });
    </script>
  </body>
</html>