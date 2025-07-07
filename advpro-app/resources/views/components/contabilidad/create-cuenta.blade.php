<!-- resources/views/components/contabilidad/create-cuenta.blade.php -->

<div class="p-6">
    <h3 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">Crear Nueva Cuenta Contable</h3>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mostrar mensaje de éxito --}}
    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('contabilidad.cuentas.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código</label>
                <input type="text" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" id="codigo" name="codigo" value="{{ old('codigo') }}" required>
            </div>
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                <input type="text" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            </div>
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Cuenta</label>
                <select id="tipo" name="tipo" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-select" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="activo" {{ old('tipo') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="pasivo" {{ old('tipo') == 'pasivo' ? 'selected' : '' }}>Pasivo</option>
                    <option value="patrimonio" {{ old('tipo') == 'patrimonio' ? 'selected' : '' }}>Patrimonio</option>
                    <option value="ingreso" {{ old('tipo') == 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                    <option value="egreso" {{ old('tipo') == 'egreso' ? 'selected' : '' }}>Egreso</option>
                    <option value="costo" {{ old('tipo') == 'costo' ? 'selected' : '' }}>Costo</option>
                </select>
            </div>
            <div>
                <label for="cuenta_padre_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cuenta Padre (Opcional)</label>
                <select id="cuenta_padre_id" name="cuenta_padre_id" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-select">
                    <option value="">Ninguna (Es una cuenta principal)</option>
                    @foreach($cuentas as $cuenta) {{-- Aquí $cuentas son todas las cuentas pasadas desde el controlador --}}
                        <option value="{{ $cuenta->id_cuenta }}" {{ old('cuenta_padre_id') == $cuenta->id_cuenta ? 'selected' : '' }}>
                            {{ $cuenta->codigo }} - {{ $cuenta->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2 flex items-center mt-2">
                <input type="checkbox" id="es_ajustable" name="es_ajustable" value="1" class="form-checkbox h-5 w-5 text-purple-600 dark:bg-gray-700 dark:border-gray-600 rounded focus:ring-purple-500" {{ old('es_ajustable') ? 'checked' : '' }}>
                <label for="es_ajustable" class="ml-2 text-sm text-gray-700 dark:text-gray-300">¿Es ajustable?</label>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Guardar Cuenta
            </button>
        </div>
    </form>
</div>
