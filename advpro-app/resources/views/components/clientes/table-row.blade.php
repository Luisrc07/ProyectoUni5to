{{-- Este parcial espera $item (un cliente) y $route_prefix --}}
<tr class="text-gray-700 dark:text-gray-400">
    <td class="px-4 py-3">
        <div class="flex items-center text-sm">
            <div>
                <p class="font-semibold truncate">{{$item->nombre}}</p>
            </div>
        </div>
    </td>
    <td class="px-4 py-3 text-sm truncate">
        {{$item->tipo_documento}}-{{$item->documento}}
    </td>
    <td class="px-4 py-3 text-sm">
        <span class="inline-block max-w-full truncate px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                {{$item->email}}
        </span>
    </td>
    <td class="px-4 py-3 text-sm truncate">
        {{$item->telefono}}
    </td>
    <td class="px-4 py-3 text-sm break-words">
        {{$item->direccion}}
    </td>
    <td class="px-4 py-3">
        <div class="flex items-center space-x-4 text-sm">
            {{-- Enlace para editar: también es mejor usar route() --}}
            <a href="{{ route($route_prefix . '.edit', $item->id) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                aria-label="Edit">
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                </svg>
            </a>
            
            {{-- Formulario para eliminar: ¡CORRECCIÓN CLAVE AQUÍ! --}}
            <form action="{{ route($route_prefix . '.destroy', $item->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE') {{-- Esto es CRUCIAL para que Laravel reconozca la solicitud DELETE --}}
                <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </form>
        </div>
    </td>
</tr>