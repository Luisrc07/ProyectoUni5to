<div class="mt-2 text-sm">
    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Cliente</span>
        <select name="id_cliente" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" selected disabled>- Seleccione -</option>
            @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}" {{ old('id_cliente') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
            @endforeach
        </select>
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Proyecto</span>
        <select name="id_proyecto" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" selected disabled>- Seleccione -</option>
            @foreach($proyectos as $proyecto)
                <option value="{{ $proyecto->id }}" {{ old('id_proyecto') == $proyecto->id ? 'selected' : '' }}>{{ $proyecto->nombre }}</option>
            @endforeach
        </select>
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Fecha del Contrato</span>
        {{-- ASEGÚRATE DE QUE ESTA LÍNEA SOLO USE old() --}}
        <input type="date" name="fecha_contrato"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            value="{{ old('fecha_contrato') }}"
        />
        @error('fecha_contrato')
            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
        @enderror
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Fecha de Entrega</span>
        {{-- ASEGÚRATE DE QUE ESTA LÍNEA SOLO USE old() --}}
        <input type="date" name="fecha_entrega"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            value="{{ old('fecha_entrega') }}"
        />
        @error('fecha_entrega')
            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
        @enderror
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Costo</span>
        <input type="number" name="costo"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="0.00"
            min="0"
            value="{{ old('costo') }}"
        />
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Estado</span>
        <select name="estado" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            <option value="finalizado" {{ old('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
            <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
        </select>
    </label>
</div>