<aside
  class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0"
>
  <div class="py-4 text-gray-500 dark:text-gray-400">
    <a
      class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200"
      href="{{ route('home') }}" {{-- Enlace a la raíz, que es tu dashboard (home) --}}
    >
      AudioVisual Pro
    </a>
    <ul class="mt-6">
      <li class="relative px-6 py-3">
        {{-- Usa route()->is() para verificar la ruta actual por su nombre o patrón --}}
        @if(request()->routeIs('dashboard') || request()->routeIs('home')) {{-- Activo si es dashboard o home --}}
        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
        @endif
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'text-gray-800 dark:text-gray-100' : '' }}" href="{{ route('dashboard') }}"> {{-- Usa route() --}}
          <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
          </svg>
          <span class="ml-4">Dashboard</span>
        </a>
      </li>

      <li class="relative px-6 py-3">
        {{-- Aquí se usa 'personal.index' que es la ruta para '/personal' --}}
        @if(request()->routeIs('personal.index')) {{-- Cambiado de 'personal/panel*' --}}
        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
        @endif
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('personal.index') ? 'text-gray-800 dark:text-gray-100' : '' }}" href="{{ route('personal.index') }}"> {{-- Usa route() --}}
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"></path>
          </svg>
          <span class="ml-4">Personal</span>
        </a>
      </li>

      <li class="relative px-6 py-3">
        {{-- Aquí se usa 'clientes.index' que es la ruta para '/clientes' --}}
        @if(request()->routeIs('clientes.index')) {{-- Cambiado de 'clientes/panel*' --}}
        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
        @endif
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('clientes.index') ? 'text-gray-800 dark:text-gray-100' : '' }}" href="{{ route('clientes.index') }}"> {{-- Usa route() --}}
          <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"></path>
          </svg>
          <span class="ml-4">Clientes</span>
        </a>
      </li>

      <li class="relative px-6 py-3">
        {{-- Aquí se usa 'contratos.index' que es la ruta para '/contratos' --}}
        @if(request()->routeIs('contratos.index')) {{-- Cambiado de 'contratos/panel*' --}}
        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
        @endif
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('contratos.index') ? 'text-gray-800 dark:text-gray-100' : '' }}" href="{{ route('contratos.index') }}"> {{-- Usa route() --}}
          <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
          </svg>
          <span class="ml-4">Contratos</span>
        </a>
      </li>

      <li class="relative px-6 py-3">
        {{-- Aquí se usa 'proyectos.index' que es la ruta para '/proyectos' --}}
        @if(request()->routeIs('proyectos.index')) {{-- Cambiado de 'proyectos/panel*' --}}
        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
        @endif
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('proyectos.index') ? 'text-gray-800 dark:text-gray-100' : '' }}" href="{{ route('proyectos.index') }}"> {{-- Usa route() --}}
          <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <span class="ml-4">Proyectos</span>
        </a>
      </li>

      <li class="relative px-6 py-3">
        {{-- Aquí se usa 'equipos.index' que es la ruta para '/equipos' --}}
        @if(request()->routeIs('equipos.index')) {{-- Cambiado de 'equipos/panel*' --}}
        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
        @endif
        <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('equipos.index') ? 'text-gray-800 dark:text-gray-100' : '' }}" href="{{ route('equipos.index') }}"> {{-- Usa route() --}}
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
          <path d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75" />
        </svg>
        <span class="ml-4">Equipos</span>
        </a>
      </li>

      {{-- Este enlace a "Tables" parece genérico. Puedes mantenerlo con url() si es una página estática o cambiarlo a route() si tienes una ruta definida para ello. --}}
      <li class="relative px-6 py-3">
    {{-- Revisa si la ruta actual es 'contabilidad.index' o cualquier otra ruta que quieras que active el botón de contabilidad. --}}
    @if(request()->routeIs('contabilidad.index'))
    <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
    @endif
    <a
        class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('contabilidad.index') ? 'text-gray-800 dark:text-gray-100' : '' }}"
        href="{{ route('contabilidad.index') }}"
    >
        <svg
            class="w-5 h-5"
            aria-hidden="true"
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            viewBox="0 0 24 24"
            stroke="currentColor"
        >
            <path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
        </svg>
        <span class="ml-4">Contabilidad</span>
   </a>
</li>

      <li class="relative px-6 py-3">
        <button
          class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
          @click="togglePagesMenu"
          aria-haspopup="true"
        >
          <span class="inline-flex items-center">
            <svg
              class="w-5 h-5"
              aria-hidden="true"
              fill="none"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
              ></path>
            </svg>
            <span class="ml-4">Pages</span>
          </span>
          <svg
            class="w-4 h-4"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              fill-rule="evenodd"
              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </button>
        <template x-if="isPagesMenuOpen">
          <ul
            x-transition:enter="transition-all ease-in-out duration-300"
            x-transition:enter-start="opacity-25 max-h-0"
            x-transition:enter-end="opacity-100 max-h-xl"
            x-transition:leave="transition-all ease-in-out duration-300"
            x-transition:leave-start="opacity-100 max-h-xl"
            x-transition:leave-end="opacity-0 max-h-0"
            class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
            aria-label="submenu"
          >
            <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
              <a class="w-full" href="{{ route('login') }}">Login</a> {{-- Usa route() --}}
            </li>

           <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
              <a class="w-full" href="{{ route('register') }}">Registro</a> {{-- Usa route() --}}
            </li>

            {{-- Estos enlaces parecen ser a archivos HTML estáticos. Si son vistas de Laravel, deberías usar route() o url() --}}
            <li
              class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
            >
              <a class="w-full" href="{{ url('pages/forgot-password') }}"> {{-- Podría ser url() si son archivos estáticos, o route() si tienes rutas definidas --}}
                Forgot password
              </a>
            </li>
            <li
              class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
            >
              <a class="w-full" href="{{ url('pages/404') }}">404</a> {{-- Idem --}}
            </li>
            <li
              class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
            >
              <a class="w-full" href="{{ url('pages/blank') }}">Blank</a> {{-- Idem --}}
            </li>
          </ul>
        </template>
      </li>
    </ul>
      <div class="px-6 my-6">
          {{-- Envuelve el botón en un enlace <a> y aplica las clases del botón al enlace --}}
          <a
              href="{{ route('register') }}"
              class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
          >
              Create account
              <span class="ml-2" aria-hidden="true">+</span>
          </a>
      </div>
  </div>
</aside>