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

            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Marca</label>
                <input type="text" class="block w-full h-11 px-5 py-2.5 bg-white shadow-xs text-gray-900 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none"
                    name="marca" value="{{ old('marca', $equipo->marca) }}" required>
            </div>
        </div>

        <div class="flex gap-x-6 mb-6">
            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Tipo de Equipo</label>
                <select name="tipo_equipo" class="block w-full h-11 px-5 py-2.5 bg-white border border-gray-300 rounded-full focus:outline-none">
                    @php
                        $tiposEquipo = ['Fotografía', 'Video', 'Sonido', 'Iluminación'];
                    @endphp
                    @foreach ($tiposEquipo as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo_equipo', $equipo->tipo_equipo) == $tipo ? 'selected' : '' }}>
                            {{ $tipo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Estado</label>
                <select name="estado" class="block w-full h-11 px-5 py-2.5 bg-white border border-gray-300 rounded-full focus:outline-none">
                    @php
                        $estados = ['Nuevo', 'Usado', 'Reparado'];
                    @endphp
                    @foreach ($estados as $estado)
                        <option value="{{ $estado }}" {{ old('estado', $equipo->estado) == $estado ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex gap-x-6 mb-6">
            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Ubicación</label>
                <input type="text" class="block w-full h-11 px-5 py-2.5 bg-white shadow-xs text-gray-900 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none"
                    name="ubicacion" value="{{ old('ubicacion', $equipo->ubicacion) }}" required>
            </div>

            <div class="w-full relative mb-6">
                <label class="block mt-2 mb-2 items-center text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Responsable</span>
                    <select name="responsable" id="responsable_edit" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="" selected disabled>Seleccionar</option>
                        @foreach ($personal as $staff)
                            <option value="{{ $staff->id }}" {{ old('responsable_id', $equipo->responsable_id) == $staff->id ? 'selected' : '' }}>
                                {{ $staff->nombre }}
                            </option>
                        @endforeach
                    </select>
                </label>
            </div>
        </div>

        <div class="w-full relative mb-6">
            <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Descripción</label>
            <textarea name="descripcion"
                class="block w-full h-20 px-5 py-2.5 bg-white border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none">{{ old('descripcion', $equipo->descripcion) }}</textarea>
        </div>

        <center>
            <button type="submit" class="w-52 h-12 shadow-sm rounded-full bg-indigo-600 hover:bg-indigo-800 transition-all duration-700 text-white text-base font-semibold leading-7">
                Guardar
            </button>
        </center>
    </form>
</x-app-layout>