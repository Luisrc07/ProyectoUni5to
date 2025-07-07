<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    {{-- Campo para Nombre o Documento --}}
    <div class="col-span-full">
        <label for="nombre_documento" class="block text-sm font-medium text-gray-700 dark:text-gray-400">
            Nombre o Documento
        </label>
        <input
            type="text"
            name="nombre_documento"
            id="nombre_documento"
            value="{{ request('nombre_documento') }}"
            placeholder="Buscar por nombre o documento"
            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
        >
    </div>

    {{-- Campo para Cargo --}}
    <div>
        <label for="cargo" class="block text-sm font-medium text-gray-700 dark:text-gray-400">
            Cargo
        </label>
        <select
            name="cargo"
            id="cargo"
            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
        >
            <option value="">- Todos los Cargos -</option>
            <option value="Producción" {{ request('cargo') == 'Producción' ? 'selected' : '' }}>Producción</option>
            <option value="Dirección" {{ request('cargo') == 'Dirección' ? 'selected' : '' }}>Dirección</option>
            <option value="Logística & Equipo" {{ request('cargo') == 'Logística & Equipo' ? 'selected' : '' }}>Logística & Equipo</option>
            <option value="Guion y Desarrollo" {{ request('cargo') == 'Guion y Desarrollo' ? 'selected' : '' }}>Guion y Desarrollo</option>
            <option value="Fotografía y Cámara" {{ request('cargo') == 'Fotografía y Cámara' ? 'selected' : '' }}>Fotografía y Cámara</option>
            <option value="Sonido" {{ request('cargo') == 'Sonido' ? 'selected' : '' }}>Sonido</option>
            <option value="Arte & Escenografía" {{ request('cargo') == 'Arte & Escenografía' ? 'selected' : '' }}>Arte & Escenografía</option>
            <option value="Iluminación y Eléctricos" {{ request('cargo') == 'Iluminación y Eléctricos' ? 'selected' : '' }}>Iluminación y Eléctricos</option>
            <option value="Postproducción" {{ request('cargo') == 'Postproducción' ? 'selected' : '' }}>Postproducción</option>
        </select>
    </div>

    {{-- Campo para Estado --}}
    <div>
        <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-400">
            Estado
        </label>
        <select
            name="estado"
            id="estado"
            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
        >
            <option value="">- Todos los Estados -</option>
            <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
</div>