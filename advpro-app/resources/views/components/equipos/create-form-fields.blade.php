<div class="max-w-md mx-auto">
    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Nombre del equipo</span>
        <input name="nombre"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Ej: Cámara Sony A7"
            value="{{ old('nombre') }}"
        />
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Descripción</span>
        <textarea name="descripcion"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-textarea"
            placeholder="Ej: Cámara profesional de alta definición">{{ old('descripcion') }}</textarea>
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Marca</span>
        <input name="marca"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Ej: Sony, Canon, Panasonic"
            value="{{ old('marca') }}"
        />
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Tipo de Equipo</span>
        <select name="tipo_equipo"
            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" selected disabled>Seleccionar</option>
            @php
                $tiposEquipo = ['Fotografía', 'Video', 'Sonido', 'Iluminación'];
            @endphp
            @foreach ($tiposEquipo as $tipo)
                <option value="{{ $tipo }}" {{ old('tipo_equipo') == $tipo ? 'selected' : '' }}>
                    {{ $tipo }}
                </option>
            @endforeach
        </select>
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Estado</span>
        <select name="estado"
            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" selected disabled>Seleccionar</option>
            @php
                $estados = ['Nuevo', 'Usado', 'Reparado'];
            @endphp
            @foreach ($estados as $estado)
                <option value="{{ $estado }}" {{ old('estado') == $estado ? 'selected' : '' }}>
                    {{ $estado }}
                </option>
            @endforeach
        </select>
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Ubicación</span>
        <input name="ubicacion"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="Ej: Estudio A, Bodega, Oficina 3"
            value="{{ old('ubicacion') }}"
        />
    </label>

    <label class="block mt-2 mb-2 items-center text-sm">
        <span class="text-gray-700 dark:text-gray-400">Responsable</span>
        <select name="responsable" id="responsable_create" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" selected disabled>Seleccionar</option>
            @foreach ($personal as $staff)
                <option value="{{ $staff->id }}" {{ old('responsable_id') == $staff->id ? 'selected' : '' }}>
                   {{ $staff->nombre }}
                </option>
            @endforeach
        </select>
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Valor del equipo</span>
        <input type="number" name="valor"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            placeholder="0.00"
        />
    </label>
</div>