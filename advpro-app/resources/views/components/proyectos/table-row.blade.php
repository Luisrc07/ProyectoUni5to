<tr class="text-gray-700 dark:text-gray-400">
    {{-- Nombre del proyecto --}}
    <td class="px-4 py-3">
        <div class="flex items-center text-sm">
            <div>
                <p class="font-semibold">{{ $item->nombre }}</p>
            </div>
        </div>
    </td>

    {{-- Descripción --}}
    <td class="px-4 py-3 text-sm">
        {{ Str::limit($item->descripcion, 50) }}
    </td>

    {{-- Estado con estilo --}}
    <td class="px-4 py-3 text-sm">
        <span class="px-2 py-1 font-semibold leading-tight rounded-full
            @if ($item->estado == 'Realizado')
                text-green-700 bg-green-100 dark:bg-green-800 dark:text-green-300
            @elseif ($item->estado == 'En proceso')
                text-orange-700 bg-orange-100 dark:bg-orange-800 dark:text-orange-300
            @elseif ($item->estado == 'En espera')
                text-blue-700 bg-blue-100 dark:bg-blue-800 dark:text-blue-300
            @elseif ($item->estado == 'Finalizado') {{-- Añadido el estilo para 'Finalizado' --}}
                text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-200
            @endif
        ">
            {{ $item->estado }}
        </span>
    </td>

    {{-- Duración estimada del video (ahora en la posición correcta) --}}
    <td class="px-4 py-3 text-sm">
        {{ $item->duracion_estimada_minutos ?? 'N/A' }} {{ $item->duracion_estimada_minutos ? 'min' : '' }}
    </td>

    {{-- Presupuesto --}}
    <td class="px-4 py-3 text-sm">
        ${{ number_format($item->presupuesto, 2, ',', '.') }}
    </td>

    {{-- Lugar --}}
    <td class="px-4 py-3 text-sm truncate">
        {{ $item->lugar ?? 'N/A' }}
    </td>

    {{-- Responsable --}}
    <td class="px-4 py-3 text-sm truncate">
        {{ $item->responsable->nombre ?? '—' }} — {{ $item->responsable->documento ?? '' }}
    </td>

    {{-- Personal Asignado (listado de nombres) --}}
    <td class="px-4 py-3 text-sm">
        @php
            $assignedPersonal = $item->personalAsignado->map(fn($p) => $p->nombre);
            $personalCount = $assignedPersonal->count();
        @endphp
        @if ($personalCount > 0)
            {{ $assignedPersonal->take(2)->implode(', ') }}
            @if ($personalCount > 2)
                y {{ $personalCount - 2 }} más
            @endif
        @else
            <span>0 Personal</span>
        @endif
    </td>

    {{-- Equipos Asignados (listado de nombres) --}}
    <td class="px-4 py-3 text-sm">
        @php
            $assignedEquipos = $item->equiposAsignados->map(fn($e) => $e->nombre);
            $equiposCount = $assignedEquipos->count();
        @endphp
        @if ($equiposCount > 0)
            {{ $assignedEquipos->take(2)->implode(', ') }}
            @if ($equiposCount > 2)
                y {{ $equiposCount - 2 }} más
            @endif
        @else
            <span>0 Equipos</span>
        @endif
    </td>

    {{-- Acciones --}}
    <td class="px-4 py-3">
        <div class="flex items-center space-x-4 text-sm">
            <a href="{{ route($route_prefix . '.edit', $item->id) }}"
                class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                aria-label="Edit">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
            </a>

            <form action="{{ route($route_prefix . '.destroy', $item->id) }}" method="POST" class="inline" id="delete-form-{{ $item->id }}">
                @csrf
                @method('DELETE')
                <button type="button" {{-- Cambiado a type="button" para evitar la sumisión directa --}}
                    class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                    aria-label="Delete"
                    onclick="showDeleteConfirmation(event, {{ $item->id }})"> {{-- Nueva llamada a función JS --}}
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 1 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z"></path></svg>
                </button>
            </form>
        </div>
    </td>
</tr>
