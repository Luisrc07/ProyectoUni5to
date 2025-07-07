<x-layouts.app>
    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Contratos
    </h2>

    <div x-data="{ isModalOpen: false }">
        <div class="mb-4">
            <x-button @click="isModalOpen = true" type="button">
                Crear Contrato
            </x-button>
        </div>

        <x-table :headers="['Cliente', 'Proyecto', 'Fecha', 'Costo', 'Estado', 'Opciones']">
            @forelse ($contratos as $contrato)
                @include('components.contratos.table-row', ['item' => $contrato, 'route_prefix' => 'contratos'])
            @empty
                <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3 text-center" colspan="6">No hay contratos para mostrar.</td>
                </tr>
            @endforelse
        </x-table>

        <div class="mt-4">
            {{ $contratos->links() }}
        </div>

        <x-create-modal
            modal_title="Crear Contrato"
            form_action="{{ route('contratos.store') }}"
            x-show="isModalOpen"
            @click.away="isModalOpen = false"
            @keydown.escape.window="isModalOpen = false"
        >
            @include('components.contratos.create-form-fields')
        </x-create-modal>
    </div>
</x-layouts.app>