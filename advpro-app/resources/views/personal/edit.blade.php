<x-layouts.app>

    <div class="flex items-center justify-center p-4">
        <div class="max-w-md p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="mb-6">
                <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Modificar Empleado <span class="text-purple-600">{{ $personal->nombre }}</span>
                </p>
                <form action="{{ route('personal.update', $personal->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">
                            Documento de identidad
                        </span>
                        <div>
                            <label class="inline-flex items-center text-sm">
                                <select name="tipo_documento" id="tipo_documento"
                                    class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                    <option value="" {{ !old('tipo_documento', $personal->tipo_documento) ? 'selected' : '' }} disabled>- Seleccione -</option>
                                    <option value="V" {{ old('tipo_documento', $personal->tipo_documento) == 'V' ? 'selected' : '' }}>V</option>
                                    <option value="J" {{ old('tipo_documento', $personal->tipo_documento) == 'J' ? 'selected' : '' }}>J</option>
                                    <option value="E" {{ old('tipo_documento', $personal->tipo_documento) == 'E' ? 'selected' : '' }}>E</option>
                                    <option value="P" {{ old('tipo_documento', $personal->tipo_documento) == 'P' ? 'selected' : '' }}>P</option>
                                </select>
                            </label>
                        </div>
                    </div>

                    <label class="block mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Número de Documento</span>
                        <input type="text" name="documento"
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="Ej: 12345678"
                            value="{{ old('documento', $personal->documento) }}"
                            required
                        />
                        @error('documento')
                            <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </label>

                    <label class="block mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Nombre</span>
                        <input type="text" name="nombre"
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="Ej: Juan Pérez"
                            value="{{ old('nombre', $personal->nombre) }}"
                            required
                        />
                    </label>

                    <label class="block mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Email</span>
                        <input type="email" name="email"
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="Ej: juan.perez@example.com"
                            value="{{ old('email', $personal->email) }}"
                        />
                    </label>

                    <label class="block mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Teléfono</span>
                        <input type="text" name="telefono"
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="Ej: 0424-1234567"
                            value="{{ old('telefono', $personal->telefono) }}"
                        />
                    </label>

                    <label class="block mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Dirección</span>
                        <input type="text" name="direccion"
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="Ej: Calle Principal, Ciudad"
                            value="{{ old('direccion', $personal->direccion) }}"
                        />
                    </label>

                    <label class="block mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Cargo</span>
                        <select name="cargo" id="cargo"
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                            <option value="" {{ !old('cargo', $personal->cargo) ? 'selected' : '' }} disabled>- Seleccione -</option>
                            <option value="Producción" {{ old('cargo', $personal->cargo) == 'Producción' ? 'selected' : '' }}>Producción</option>
                            <option value="Dirección" {{ old('cargo', $personal->cargo) == 'Dirección' ? 'selected' : '' }}>Dirección</option>
                            <option value="Logística & Equipo" {{ old('cargo', $personal->cargo) == 'Logística & Equipo' ? 'selected' : '' }}>Logística & Equipo</option>
                            <option value="Guion y Desarrollo" {{ old('cargo', $personal->cargo) == 'Guion y Desarrollo' ? 'selected' : '' }}>Guion y Desarrollo</option>
                            <option value="Fotografía y Cámara" {{ old('cargo', $personal->cargo) == 'Fotografía y Cámara' ? 'selected' : '' }}>Fotografía y Cámara</option>
                            <option value="Sonido" {{ old('cargo', $personal->cargo) == 'Sonido' ? 'selected' : '' }}>Sonido</option>
                            <option value="Arte & Escenografía" {{ old('cargo', $personal->cargo) == 'Arte & Escenografía' ? 'selected' : '' }}>Arte & Escenografía</option>
                            <option value="Iluminación y Eléctricos" {{ old('cargo', $personal->cargo) == 'Iluminación y Eléctricos' ? 'selected' : '' }}>Iluminación y Eléctricos</option>
                            <option value="Postproducción" {{ old('cargo', $personal->cargo) == 'Postproducción' ? 'selected' : '' }}>Postproducción</option>
                        </select>
                    </label>

                    <label class="block mt-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Estado</span>
                        <select name="estado" id="estado"
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                            <option value="Activo" {{ old('estado', $personal->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ old('estado', $personal->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </label>


                    <div class="flex items-center justify-end mt-6 space-x-4">
                        <a href="{{ route('personal.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 hover:border-gray-500 focus:border-gray-500 focus:outline-none focus:shadow-outline-gray">
                            Volver
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
