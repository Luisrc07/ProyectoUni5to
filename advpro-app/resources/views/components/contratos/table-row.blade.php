<tr class="text-gray-700 dark:text-gray-400">
    <td class="px-4 py-3">
        <div class="flex items-center text-sm">
            <div>
                <p class="font-semibold">{{ $item->cliente->nombre }}</p>
            </div>
        </div>
    </td>
    <td class="px-4 py-3 text-sm">
        {{ $item->proyecto->nombre }}
    </td>
    <td class="px-4 py-3 text-sm">
        {{ $item->fecha_contrato->format('d/m/Y') }}
    </td>
    <td class="px-4 py-3 text-sm">
        {{ number_format($item->costo, 2) }}
    </td>
    <td class="px-4 py-3 text-sm">
        <span class="inline-block px-2 py-1 font-semibold leading-tight rounded-full
            {{ $item->estado == 'activo' ? 'text-green-700 bg-green-100' :
               ($item->estado == 'inactivo' ? 'text-red-700 bg-red-100' :
               'text-yellow-700 bg-yellow-100') }}">
            {{ ucfirst($item->estado) }}
        </span>
    </td>
    <td class="px-4 py-3 text-sm"> {{-- Nueva columna para el serial --}}
        {{ $item->serial }}
    </td>
    <td class="px-4 py-3">
        <div class="flex items-center space-x-4 text-sm">
            <a href="{{ route($route_prefix . '.edit', $item->id) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                aria-label="Edit">
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                </svg>
            </a>
            
            {{-- Botón para Imprimir PDF --}}
            <a href="{{ route($route_prefix . '.pdf', $item->id) }}" target="_blank" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                aria-label="Print">
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 4h10v2H5V4zm0 4h10v2H5V8zm0 4h10v2H5v-2zM3 2h14a2 2 0 012 2v12a2 2 0 01-2 2H3a2 2 0 01-2-2V4a2 2 0 012-2zm2 14h10v-2H5v2z"/>
                </svg>
            </a>

            <form action="{{ route($route_prefix . '.destroy', $item->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </form>
        </div>
    </td>
</tr>