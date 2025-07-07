<x-layouts.app>
    <form action="{{ route('equipos.update', $equipo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Editar Equipo</h1>
            <a href="{{ route('equipos.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Volver</a>
        </div>

        <div class="flex gap-x-6 mb-6">
            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Nombre del equipo</label>
                <input type="text" class="block w-full h-11 px-5 py-2.5 bg-white shadow-xs text-gray-900 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none"
                    name="nombre" value="{{ old('nombre', $equipo->nombre) }}" required>
            </div>
            @error('nombre') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Marca</label>
                <input type="text" class="block w-full h-11 px-5 py-2.5 bg-white shadow-xs text-gray-900 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none"
                    name="marca" value="{{ old('marca', $equipo->marca) }}" required>
            </div>
            @error('marca') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-x-6 mb-6">
            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Tipo de Equipo</label>
                <select name="tipo_equipo" class="block w-full h-11 px-5 py-2.5 bg-white border border-gray-300 rounded-full focus:outline-none">
                    <option value="" {{ !old('tipo_equipo', $equipo->tipo_equipo) ? 'selected' : '' }} disabled>Seleccionar</option>
                    <option value="Cámara" {{ old('tipo_equipo', $equipo->tipo_equipo) == 'Cámara' ? 'selected' : '' }}>Cámara</option>
                    <option value="Lente" {{ old('tipo_equipo', $equipo->tipo_equipo) == 'Lente' ? 'selected' : '' }}>Lente</option>
                    <option value="Iluminación" {{ old('tipo_equipo', $equipo->tipo_equipo) == 'Iluminación' ? 'selected' : '' }}>Iluminación</option>
                    <option value="Sonido" {{ old('tipo_equipo', $equipo->tipo_equipo) == 'Sonido' ? 'selected' : '' }}>Sonido</option>
                    <option value="Trípode" {{ old('tipo_equipo', $equipo->tipo_equipo) == 'Trípode' ? 'selected' : '' }}>Trípode</option>
                    <option value="Estabilizador" {{ old('tipo_equipo', $equipo->tipo_equipo) == 'Estabilizador' ? 'selected' : '' }}>Estabilizador</option>
                    <option value="Micrófono" {{ old('tipo_equipo', $equipo->tipo_equipo) == 'Micrófono' ? 'selected' : '' }}>Micrófono</option>
                    <option value="Monitor" {{ old('tipo_equipo', $equipo->tipo_equipo) == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                    <option value="Batería" {{ old('tipo_equipo', $equipo->tipo_equipo) == 'Batería' ? 'selected' : '' }}>Batería</option>
                    <option value="Cable" {{ old('tipo_equipo', $equipo->tipo_equipo) == 'Cable' ? 'selected' : '' }}>Cable</option>
                    <option value="Otro" {{ old('tipo_equipo', $equipo->tipo_equipo) == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>
            @error('tipo_equipo') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Estado</label>
                <select name="estado" class="block w-full h-11 px-5 py-2.5 bg-white border border-gray-300 rounded-full focus:outline-none">
                    <option value="" {{ !old('estado', $equipo->estado) ? 'selected' : '' }} disabled>Seleccionar</option>
                    <option value="Nuevo" {{ old('estado', $equipo->estado) == 'Nuevo' ? 'selected' : '' }}>Nuevo</option>
                    <option value="Usado" {{ old('estado', $equipo->estado) == 'Usado' ? 'selected' : '' }}>Usado</option>
                    <option value="Reparado" {{ old('estado', $equipo->estado) == 'Reparado' ? 'selected' : '' }}>Reparado</option>
                </select>
            </div>
            @error('estado') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-x-6 mb-6">
            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Ubicación</label>
                <input type="text" class="block w-full h-11 px-5 py-2.5 bg-white shadow-xs text-gray-900 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none"
                    name="ubicacion" value="{{ old('ubicacion', $equipo->ubicacion) }}" required>
            </div>
            @error('ubicacion') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

            <div class="w-full relative mb-6">
                <label class="block mt-2 mb-2 items-center text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Responsable</span>
                    <select name="responsable" id="responsable_edit" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="" {{ !old('responsable', $equipo->responsable) ? 'selected' : '' }}>Seleccionar</option>
                        @foreach ($personal as $staff)
                            <option value="{{ $staff->id }}" {{ old('responsable', $equipo->responsable) == $staff->id ? 'selected' : '' }}>
                                {{ $staff->nombre }}
                            </option>
                        @endforeach
                    </select>
                </label>
                @error('responsable') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="w-full relative mb-6">
            <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Valor del equipo</label>
            <input type="number" name="valor" step="0.01"
                class="block w-full h-11 px-5 py-2.5 bg-white shadow-xs text-gray-900 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none"
                placeholder="0.00"
                value="{{ old('valor', $equipo->valor) }}"
                required
            />
        </div>
        @error('valor') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

        <div class="w-full relative mb-6">
            <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Descripción</label>
            <textarea name="descripcion"
                class="block w-full h-20 px-5 py-2.5 bg-white border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none">{{ old('descripcion', $equipo->descripcion) }}</textarea>
        </div>
        @error('descripcion') <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

        <center>
            <button type="submit" class="w-52 h-12 shadow-sm rounded-full bg-indigo-600 hover:bg-indigo-800 transition-all duration-700 text-white text-base font-semibold leading-7">
                Guardar
            </button>
        </center>
    </form>
</x-layouts.app>