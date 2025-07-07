<div class="max-w-md mx-auto">
    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Nombre del equipo</span>
        <input name="nombre"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Ej: Cámara Sony A7"
            value="{{ old('nombre') }}"
            required
        />
    </label>
    @error('nombre') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Descripción</span>
        <textarea name="descripcion"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-textarea"
            placeholder="Ej: Cámara profesional de alta definición"
        >{{ old('descripcion') }}</textarea>
    </label>
    @error('descripcion') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Marca</span>
        <input name="marca"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Ej: Sony, Canon, Panasonic"
            value="{{ old('marca') }}"
            required
        />
    </label>
    @error('marca') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Tipo de Equipo</span>
        <select name="tipo_equipo"
            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
            required>
            <option value="" {{ !old('tipo_equipo') ? 'selected' : '' }} disabled>Seleccionar</option>
            <option value="Cámara" {{ old('tipo_equipo') == 'Cámara' ? 'selected' : '' }}>Cámara</option>
            <option value="Lente" {{ old('tipo_equipo') == 'Lente' ? 'selected' : '' }}>Lente</option>
            <option value="Iluminación" {{ old('tipo_equipo') == 'Iluminación' ? 'selected' : '' }}>Iluminación</option>
            <option value="Sonido" {{ old('tipo_equipo') == 'Sonido' ? 'selected' : '' }}>Sonido</option>
            <option value="Trípode" {{ old('tipo_equipo') == 'Trípode' ? 'selected' : '' }}>Trípode</option>
            <option value="Estabilizador" {{ old('tipo_equipo') == 'Estabilizador' ? 'selected' : '' }}>Estabilizador</option>
            <option value="Micrófono" {{ old('tipo_equipo') == 'Micrófono' ? 'selected' : '' }}>Micrófono</option>
            <option value="Monitor" {{ old('tipo_equipo') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
            <option value="Batería" {{ old('tipo_equipo') == 'Batería' ? 'selected' : '' }}>Batería</option>
            <option value="Cable" {{ old('tipo_equipo') == 'Cable' ? 'selected' : '' }}>Cable</option>
            <option value="Otro" {{ old('tipo_equipo') == 'Otro' ? 'selected' : '' }}>Otro</option>
        </select>
    </label>
    @error('tipo_equipo') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Estado</span>
        <select name="estado"
            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
            required>
            <option value="" {{ !old('estado') ? 'selected' : '' }} disabled>Seleccionar</option>
            <option value="Nuevo" {{ old('estado') == 'Nuevo' ? 'selected' : '' }}>Nuevo</option>
            <option value="Usado" {{ old('estado') == 'Usado' ? 'selected' : '' }}>Usado</option>
            <option value="Reparado" {{ old('estado') == 'Reparado' ? 'selected' : '' }}>Reparado</option>
        </select>
    </label>
    @error('estado') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Ubicación</span>
        <input name="ubicacion"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Ej: Estudio A, Bodega, Oficina 3"
            value="{{ old('ubicacion') }}"
            required
        />
    </label>
    @error('ubicacion') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

    <label class="block mt-2 mb-2 items-center text-sm">
        <span class="text-gray-700 dark:text-gray-400">Responsable</span>
        <select name="responsable" id="responsable_create" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" {{ !old('responsable') ? 'selected' : '' }}>Seleccionar</option>
            @foreach ($personal as $staff)
                <option value="{{ $staff->id }}" {{ old('responsable') == $staff->id ? 'selected' : '' }}>
                   {{ $staff->nombre }}
                </option>
            @endforeach
        </select>
    </label>
    @error('responsable') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Cantidad</span>
        value="{{ old('cantidad') }}"
        required
        />
    </label>
    @error('cantidad') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Valor Unitario</span>
        <input type="number" name="valor" step="0.01"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="0.00"
            required
        />
    </label>
    @error('valor') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
</div>