{{-- resources/views/components/personal/table-row.blade.php --}}

<tr class="text-gray-700 dark:text-gray-400">
    <td class="px-4 py-3">
        <div class="flex items-center text-sm">
            <div>
                <p class="font-semibold truncate">{{ $personal->nombre }}</p>
            </div>
        </div>
    </td>
    <td class="px-4 py-3 text-sm truncate">
        {{ $personal->tipo_documento }}-{{ $personal->documento }}
    </td>
    <td class="px-4 py-3 text-sm truncate">
        {{ $personal->email }}
    </td>
    <td class="px-4 py-3 text-sm truncate">
        {{ $personal->telefono }}
    </td>
    <td class="px-4 py-3 text-sm break-words">
        {{ $personal->direccion }}
    </td>
    <td class="px-4 py-3 text-sm truncate">
        {{ $personal->cargo }}
    </td>
    <td class="px-4 py-3">
        @php
            $statusClass = $personal->estado == 'Activo' ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100';
        @endphp
        <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $statusClass }}">
            {{ $personal->estado }}
        </span>
    </td>
    <td class="px-4 py-3">
        <div class="flex items-center space-x-4 text-sm">
            {{-- Botón de edición --}}
            <a href="{{ route('personal.edit', $personal->id) }}"
                class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                aria-label="Edit">
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                </svg>
            </a>

            {{-- Formulario para cambiar estado (activar/desactivar) --}}
            <form action="{{ route('personal.destroy', $personal->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE') {{-- O PATCH si prefieres para cambiar estado --}}
                <button type="submit"
                    class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                    aria-label="Toggle Status"
                    onclick="return confirm('¿Estás seguro de que quieres {{ $personal->estado == 'Activo' ? 'desactivar' : 'activar' }} a {{ $personal->nombre }}?');">
                    @if($personal->estado == 'Activo')
                        {{-- Icono para desactivar --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    @else
                        {{-- Icono para activar (ej. un checkmark o un ojo) --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                </button>
            </form>
        </div>
    </td>
</tr>
