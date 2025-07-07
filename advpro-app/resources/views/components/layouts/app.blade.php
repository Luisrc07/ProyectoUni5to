<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
  <head>
    <x-layouts.partials.head />
  </head>
  <body class="bg-gray-50 dark:bg-gray-900"> {{-- CAMBIO CLAVE: Clases de fondo aplicadas al <body> --}}
    <div
      class="flex min-h-screen" {{-- CLASE DE FONDO REMOVIDA DE AQUÍ --}}
      :class="{ 'overflow-hidden': isSideMenuOpen }"
    >
      <x-layouts.partials.desktop-sidebar />

      <x-layouts.partials.mobile-sidebar />

      <div class="flex flex-col flex-1 w-full">
        <x-layouts.partials.header />

        <main class="flex-grow">
            <div class="p-6 ">
                {{ $slot }}
            </div>
        </main>
      </div>
    </div>

    <x-layouts.partials.alerts />

  </body>
</html>