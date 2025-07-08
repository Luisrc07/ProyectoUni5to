<x-layouts.app>

<div class="flex items-center justify-center p-4">
  <div class="max-w-md p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <div class="mb-6">
      <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
        Modificar Contrato
      </p>
      <form action="{{ url('contratos', ['contrato' => $contrato->id]) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Mostrar el serial del contrato (solo lectura) --}}
        <label class="block mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-400">Serial del Contrato</span>
            <input type="text" value="{{ $contrato->serial }}" readonly
                class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input cursor-not-allowed"
            />
        </label>

        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Cliente</span>
          <select name="id_cliente" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" {{ !old('id_cliente', $contrato->id_cliente ?? '') ? 'selected' : '' }}>- Seleccione -</option>
            @foreach($clientes as $cliente)
              <option value="{{ $cliente->id }}" {{ (old('id_cliente', $contrato->id_cliente) == $cliente->id) ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
            @endforeach
          </select>
        </label>

        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Proyecto</span>
          <select name="id_proyecto" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" {{ !old('id_proyecto', $contrato->id_proyecto ?? '') ? 'selected' : '' }}>- Seleccione -</option>
            @foreach($proyectos as $proyecto)
              <option value="{{ $proyecto->id }}" {{ (old('id_proyecto', $contrato->id_proyecto) == $proyecto->id) ? 'selected' : '' }}>{{ $proyecto->nombre }}</option>
            @endforeach
          </select>
        </label>

        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Fecha del Contrato</span>
          <input type="date" name="fecha_contrato"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            value="{{ old('fecha_contrato', $contrato->fecha_contrato->format('Y-m-d')) }}"
          />
        </label>

        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Costo</span>
          <input type="number" name="costo"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="0.00"
            value="{{ old('costo', $contrato->costo) }}"
          />
        </label>

        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Estado</span>
          <select name="estado" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="activo" {{ (old('estado', $contrato->estado) == 'activo') ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ (old('estado', $contrato->estado) == 'inactivo') ? 'selected' : '' }}>Inactivo</option>
            <option value="finalizado" {{ (old('estado', $contrato->estado) == 'finalizado') ? 'selected' : '' }}>Finalizado</option>
            <option value="pendiente" {{ (old('estado', $contrato->estado) == 'pendiente') ? 'selected' : '' }}>Pendiente</option>
          </select>
        </label>

        <div class="flex items-center justify-end mt-6 space-x-4">
          <a href="{{ route('contratos.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 hover:border-gray-500 focus:border-gray-500 focus:outline-none focus:shadow-outline-gray">
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