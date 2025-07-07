<x-layouts.app>
    <div class="container mx-auto p-4">
        <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 dark:bg-gray-800">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Detalles del Proyecto: {{ $proyecto->nombre }}</h1>
                <a href="{{ route('proyectos.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    Volver a la lista
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Descripción:</p>
                    <p class="text-gray-900 dark:text-gray-100">{{ $proyecto->descripcion }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Estado:</p>
                    <span class="px-2 py-1 font-semibold leading-tight rounded-full
                        @if ($proyecto->estado == 'Realizado')
                            text-green-700 bg-green-100 dark:bg-green-700 dark:text-white
                        @elseif ($proyecto->estado == 'En proceso')
                            text-orange-700 bg-orange-100 dark:bg-orange-700 dark:text-white
                        @elseif ($proyecto->estado == 'En espera')
                            text-blue-700 bg-blue-100 dark:bg-blue-700 dark:text-white
                        @endif
                    ">
                        {{ $proyecto->estado }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Fecha de Inicio:</p>
                    <p class="text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Fecha de Fin:</p>
                    <p class="text-gray-900 dark:text-gray-100">{{ $proyecto->fecha_fin ? \Carbon\Carbon::parse($proyecto->fecha_fin)->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Presupuesto:</p>
                    <p class="text-gray-900 dark:text-gray-100">${{ number_format($proyecto->presupuesto, 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Lugar:</p>
                    <p class="text-gray-900 dark:text-gray-100">{{ $proyecto->lugar ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Responsable:</p>
                    <p class="text-gray-900 dark:text-gray-100">
                        {{ $proyecto->responsable->nombre ?? 'N/A' }}
                        @if($proyecto->responsable)
                            <span class="text-xs text-gray-500">({{ $proyecto->responsable->documento }})</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Personal Asignado --}}
            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Personal Asignado</h2>
                @if ($proyecto->personalAsignado->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400">No hay personal asignado a este proyecto.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-700">
                                    <th class="px-4 py-3">Nombre</th>
                                    <th class="px-4 py-3">Documento</th>
                                    <th class="px-4 py-3">Fecha Asignación</th>
                                    <th class="px-4 py-3">Fecha Fin Asignación</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                @foreach ($proyecto->personalAsignado as $personal)
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3 text-sm">{{ $personal->nombre }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $personal->documento }}</td>
                                        <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($personal->pivot->fecha_asignacion)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $personal->pivot->fecha_fin_asignacion ? \Carbon\Carbon::parse($personal->pivot->fecha_fin_asignacion)->format('d/m/Y') : 'Indefinido' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Equipos Asignados --}}
            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Equipos Asignados</h2>
                @if ($proyecto->equiposAsignados->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400">No hay equipos asignados a este proyecto.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-700">
                                    <th class="px-4 py-3">Nombre Equipo</th>
                                    <th class="px-4 py-3">Marca</th>
                                    <th class="px-4 py-3">Tipo</th>
                                    <th class="px-4 py-3">Cantidad</th>
                                    <th class="px-4 py-3">Fecha Asignación</th>
                                    <th class="px-4 py-3">Fecha Fin Asignación</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                @foreach ($proyecto->equiposAsignados as $equipo)
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3 text-sm">{{ $equipo->nombre }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $equipo->marca }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $equipo->tipo_equipo }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $equipo->pivot->cantidad }}</td>
                                        <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($equipo->pivot->fecha_asignacion)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $equipo->pivot->fecha_fin_asignacion ? \Carbon\Carbon::parse($equipo->pivot->fecha_fin_asignacion)->format('d/m/Y') : 'Indefinido' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>

