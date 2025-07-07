<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crear cuenta - Tu Aplicación</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    @vite(['resources/css/tailwind.output.css'])
    <script
      src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
      defer
    ></script>
    <script src="{{asset('js/init-alpine.js')}}"></script>
  </head>
  <body>
    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
      <div
        class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800"
      >
        <div class="flex flex-col overflow-y-auto md:flex-row">
          <div class="h-32 md:h-auto md:w-1/2">
            <img
              aria-hidden="true"
              class="object-cover w-full h-full dark:hidden"
              src="{{asset('img/create-account-office.jpeg')}}"
              alt="Office"
            />
            <img
              aria-hidden="true"
              class="hidden object-cover w-full h-full dark:block"
              src="{{asset('img/create-account-office-dark.jpeg')}}"
              alt="Office"
            />
          </div>
          <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
            <div class="w-full">
              <h1
                class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200"
              >
                Crear cuenta
              </h1>

              {{-- FORMULARIO DE REGISTRO --}}
              <form method="POST" action="{{ route('register') }}">
                @csrf {{-- ¡IMPORTANTE! Directiva CSRF para seguridad --}}

                {{-- Display validation errors --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <label class="block text-sm">
                  <span class="text-gray-700 dark:text-gray-400">Nombre Completo</span>
                  <input
                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                    placeholder="Jane Doe"
                    type="text" {{-- Added type for name --}}
                    name="name" {{-- IMPORTANT: Added name attribute --}}
                    value="{{ old('name') }}" {{-- Retain old input value --}}
                    required autofocus {{-- Added required and autofocus --}}
                  />
                  @error('name') {{-- Display specific error for 'name' --}}
                    <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                  @enderror
                </label>

                <label class="block mt-4 text-sm">
                  <span class="text-gray-700 dark:text-gray-400">Email</span>
                  <input
                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                    placeholder="usuario@ejemplo.com"
                    type="email" {{-- IMPORTANT: Changed type to email --}}
                    name="email" {{-- IMPORTANT: Added name attribute --}}
                    value="{{ old('email') }}" {{-- Retain old input value --}}
                    required
                  />
                  @error('email') {{-- Display specific error for 'email' --}}
                    <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                  @enderror
                </label>

                <label class="block mt-4 text-sm">
                  <span class="text-gray-700 dark:text-gray-400">Contraseña</span>
                  <input
                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                    placeholder="***************"
                    type="password"
                    name="password" {{-- IMPORTANT: Added name attribute --}}
                    required
                  />
                  @error('password') {{-- Display specific error for 'password' --}}
                    <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                  @enderror
                </label>

                <label class="block mt-4 text-sm">
                  <span class="text-gray-700 dark:text-gray-400">
                    Confirmar contraseña
                  </span>
                  <input
                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                    placeholder="***************"
                    type="password"
                    name="password_confirmation" {{-- IMPORTANT: Added name attribute (must be password_confirmation) --}}
                    required
                  />
                </label>

                {{-- Changed <a> to <button type="submit"> --}}
                <button
                  type="submit"
                  class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                >
                  Crear cuenta
                </button>

              </form> {{-- Close the form tag --}}

              <hr class="my-8" />

              <p class="mt-4">
                <a
                  class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline"
                  href="{{ route('login') }}" {{-- Use named route for consistency --}}
                >
                  ¿Ya tienes una cuenta? Login
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>