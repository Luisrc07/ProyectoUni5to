{{-- resources/views/components/personal/create-form-fields.blade.php --}}

<div class="max-w-md mx-auto">
    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Nombre</span>
        <input name="nombre"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Ej: Ana Rodríguez"
            value="{{ old('nombre') }}"
            required
        />
    </label>

    <div class="mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Documento de identidad</span>
        <div>
            <label class="inline-flex items-center text-sm">
                <select name="tipo_documento" id="tipo_documento_create"
                    class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value="" {{ !old('tipo_documento') ? 'selected' : '' }} disabled>- Seleccione -</option>
                    <option value="V" {{ old('tipo_documento') == 'V' ? 'selected' : '' }}>V</option>
                    <option value="J" {{ old('tipo_documento') == 'J' ? 'selected' : '' }}>J</option>
                    <option value="E" {{ old('tipo_documento') == 'E' ? 'selected' : '' }}>E</option>
                    <option value="P" {{ old('tipo_documento') == 'P' ? 'selected' : '' }}>P</option>
                </select>
            </label>
        </div>
    </div>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Número de Documento</span>
        <input type="text" name="documento"
            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Ej: 12345678"
            value="{{ old('documento') }}"
            required
        />
        @error('documento')
            <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Email</span>
        <input type="email" name="email"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Ej: ana.rodriguez@example.com"
            value="{{ old('email') }}"
        />
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Teléfono</span>
        <input type="text" name="telefono"
            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Ej: 0412-9876543"
            value="{{ old('telefono') }}"
        />
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Dirección</span>
        <input type="text" name="direccion"
            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Ej: Av. Principal, Edificio X, Apt. 1A"
            value="{{ old('direccion') }}"
        />
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Cargo</span>
        <select name="cargo" id="cargo_create"
            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" {{ !old('cargo') ? 'selected' : '' }} disabled>- Seleccione -</option>
            <option value="Producción" {{ old('cargo') == 'Producción' ? 'selected' : '' }}>Producción</option>
            <option value="Dirección" {{ old('cargo') == 'Dirección' ? 'selected' : '' }}>Dirección</option>
            <option value="Logística & Equipo" {{ old('cargo') == 'Logística & Equipo' ? 'selected' : '' }}>Logística & Equipo</option>
            <option value="Guion y Desarrollo" {{ old('cargo') == 'Guion y Desarrollo' ? 'selected' : '' }}>Guion y Desarrollo</option>
            <option value="Fotografía y Cámara" {{ old('cargo') == 'Fotografía y Cámara' ? 'selected' : '' }}>Fotografía y Cámara</option>
            <option value="Sonido" {{ old('cargo') == 'Sonido' ? 'selected' : '' }}>Sonido</option>
            <option value="Arte & Escenografía" {{ old('cargo') == 'Arte & Escenografía' ? 'selected' : '' }}>Arte & Escenografía</option>
            <option value="Iluminación y Eléctricos" {{ old('cargo') == 'Iluminación y Eléctricos' ? 'selected' : '' }}>Iluminación y Eléctricos</option>
            <option value="Postproducción" {{ old('cargo') == 'Postproducción' ? 'selected' : '' }}>Postproducción</option>
        </select>
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Estado</span>
        <select name="estado" id="estado_create"
            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="Activo" {{ old('estado', 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
    </label>
</div>
