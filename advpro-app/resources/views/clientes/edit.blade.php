<x-layouts.app>

<div class="flex items-center justify-center p-4">
  <div class=" max-w-md p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <div class="mb-6">
      <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
        Modificar Cliente
      </p>
      <form action="{{ url('clientes', ['cliente' => $cliente->id]) }}" method="POST" >
        @csrf
        @method('PUT')

  <div class="mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-400">
          Documento de identidad
      </span>
      <div>
          <label class="inline-flex items-center text-sm">
              <select name="tipo_documento" id="tipo_documento" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                  {{-- Opción por defecto (vacía o para seleccionar), no necesariamente preseleccionada --}}
                  <option value="" {{ !old('tipo_documento', $cliente->tipo_documento ?? '') ? 'selected' : '' }}>- Seleccione -</option>
              
                  {{-- Opciones con los valores correctos y lógica de preselección --}}
                  <option value="V" {{ (old('tipo_documento', $cliente->tipo_documento ?? '') == 'V' || $cliente->tipo_documento == '1') ? 'selected' : '' }}>V</option>
                  <option value="J" {{ (old('tipo_documento', $cliente->tipo_documento ?? '') == 'J' || $cliente->tipo_documento == '2') ? 'selected' : '' }}>J</option>
                  <option value="E" {{ (old('tipo_documento', $cliente->tipo_documento ?? '') == 'E' || $cliente->tipo_documento == '3') ? 'selected' : '' }}>E</option>
                  <option value="G" {{ (old('tipo_documento', $cliente->tipo_documento ?? '') == 'G' || $cliente->tipo_documento == '4') ? 'selected' : '' }}>G</option>
              </select>
          </label>
          <label class="inline-flex items-center text-sm">
              <input name="documento"
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  placeholder="Cedula/Rif"
                  value="{{ old('documento', $cliente->documento) }}" {{-- Usa old() también aquí --}}
              />
          </label>
      </div>
  </div>
        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Nombre y Apellido</span>
          <input name="nombre"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Juan Luis Guerra"
            value="{{$cliente->nombre}}"
          />
        </label>
        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Email</span>
          <input type="email" name="email"
            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            value="{{$cliente->email}}"
        </label>
        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Telefono</span>
          <input type="text" name="telefono"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="0424-0426-0414-0416-0412"
            value="{{$cliente->telefono}}"
          />
        </label>
        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Direccion</span>
          <input type="text" name="direccion"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Cualquier calle, ciudad, estado"
            value="{{$cliente->direccion}}"
          />
        </label>
        <div class="flex items-center justify-end mt-6 space-x-4">
          <a href="{{ route('clientes.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 hover:border-gray-500 focus:border-gray-500 focus:outline-none focus:shadow-outline-gray">
         Volver
          </a>
          <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
</x-layouts.app>