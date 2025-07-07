<x-layouts.app>
    <div class="flex items-center justify-center p-4">
        <div class="max-w-4xl p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="mb-6">
                <div class="flex justify-between items-center mb-6">
                    <p class="text-2xl font-semibold text-gray-800 dark:text-gray-300">Editar Proyecto</p>
                    <a href="{{ route('proyectos.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        Volver
                    </a>
                </div>

                <form action="{{ route('proyectos.update', ['proyecto' => $proyecto->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Incluye el componente de campos del formulario, pasándole el proyecto actual y las opciones --}}
                    @include('components.proyectos.create-form-fields', [
                        'proyecto' => $proyecto,
                        'personal' => $personal,
                        'equipos' => $equipos
                    ])

                    {{-- Botones --}}
                    <div class="flex items-center justify-end mt-6 space-x-4">
                        <a href="{{ route('proyectos.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 hover:border-gray-500 focus:border-gray-500 focus:outline-none focus:shadow-outline-gray">
                            Volver
                        </a>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
