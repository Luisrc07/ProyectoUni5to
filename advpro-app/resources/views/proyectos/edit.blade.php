<x-layouts.app>
    {{-- Contenedor principal con padding reducido para una interfaz compacta --}}
    <div class="p-2">
        {{-- Contenedor del formulario principal --}}
        <div class="max-w-2xl p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 w-full mx-auto">
            <div class="mb-4">
                {{-- Encabezado del formulario y enlace para volver al panel --}}
                <div class="flex justify-between items-center mb-4">
                    <p class="text-xl font-semibold text-gray-800 dark:text-gray-300">Editar Proyecto</p>
                    <a href="{{ route('proyectos.index') }}" class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        Volver al Panel
                    </a>
                </div>

                {{-- Formulario de edición del proyecto --}}
                <form action="{{ route('proyectos.update', ['proyecto' => $proyecto->id]) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Método HTTP para actualizar recursos --}}

                    {{-- Sección de Errores de Validación: muestra cualquier error de validación --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Oops! Hubo algunos problemas con tu entrada:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Incluye el componente de campos del formulario.
                         Este componente contiene toda la lógica de los campos (nombre, descripción, duración, etc.),
                         así como la gestión dinámica de personal y equipos, y el cálculo del presupuesto.
                         Recibe el objeto $proyecto actual, el personal disponible y los equipos disponibles. --}}
                    @include('components.proyectos.create-form-fields', [
                        'proyecto' => $proyecto,
                        'personal' => $personal,
                        'equipos' => $equipos
                    ])

                    {{-- Contenedor para los botones de acción --}}
                    <div class="flex items-center justify-end mt-4 space-x-3">
                        {{-- Botón para volver sin guardar cambios --}}
                        <a href="{{ route('proyectos.index') }}" class="px-3 py-1.5 text-sm font-medium text-gray-700 transition-colors duration-150 border border-gray-300 rounded-md dark:text-gray-400 hover:border-gray-500 focus:border-gray-500 focus:outline-none focus:shadow-outline-gray">
                            Volver
                        </a>
                        {{-- Botón para enviar el formulario y guardar los cambios --}}
                        <button type="submit" class="px-3 py-1.5 text-sm font-medium text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>