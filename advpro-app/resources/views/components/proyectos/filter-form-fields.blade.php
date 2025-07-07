{{-- resources/views/components/proyectos/filter-form-fields.blade.php --}}
@props(['personal', 'equipos']) {{-- Ahora pasamos 'equipos' también --}}

{{-- Importante: El ID 'filter-form' es usado por el JavaScript para copiar los filtros al formulario de exportación PDF --}}
<div class="space-y-6" id="filter-form"> {{-- Asegúrate que el ID esté aquí --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
        {{-- Filtro por Responsable (select) --}}
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="responsable_id">
                Responsable
            </label>
            <select name="responsable_id" id="responsable_id"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 form-select">
                <option value="">Todos los responsables</option>
                @foreach($personal as $person)
                    <option value="{{ $person->id }}" {{ (request('responsable_id') == $person->id) ? 'selected' : '' }}>
                        {{ $person->nombre }} — {{ $person->documento }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filtro por Estado --}}
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="estado">
                Estado del Proyecto
            </label>
            <select name="estado" id="estado"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 form-select">
                <option value="">Todos los estados</option>
                <option value="En espera" {{ (request('estado') == 'En espera') ? 'selected' : '' }}>En espera</option>
                <option value="En proceso" {{ (request('estado') == 'En proceso') ? 'selected' : '' }}>En proceso</option>
                <option value="Realizado" {{ (request('estado') == 'Realizado') ? 'selected' : '' }}>Realizado</option>
            </select>
        </div>

        {{-- Filtro por Fecha de Inicio (único campo) --}}
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="fecha_inicio">
                Fecha Inicio
            </label>
            <input type="date" name="fecha_inicio" id="fecha_inicio"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 form-input"
                value="{{ request('fecha_inicio') }}" />
        </div>

        {{-- Filtro por Fecha de Fin (único campo) --}}
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="fecha_fin">
                Fecha Fin
            </label>
            <input type="date" name="fecha_fin" id="fecha_fin"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 form-input"
                value="{{ request('fecha_fin') }}" />
        </div>

        {{-- Filtro por Presupuesto Mínimo --}}
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="presupuesto_min">
                Presupuesto Mínimo
            </label>
            <input type="number" step="0.01" name="presupuesto_min" id="presupuesto_min"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 form-input"
                placeholder="Ej: 100.00" value="{{ request('presupuesto_min') }}" />
        </div>

        {{-- Filtro por Presupuesto Máximo --}}
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="presupuesto_max">
                Presupuesto Máximo
            </label>
            <input type="number" step="0.01" name="presupuesto_max" id="presupuesto_max"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 form-input"
                placeholder="Ej: 5000.00" value="{{ request('presupuesto_max') }}" />
        </div>

        {{-- Nuevo: Filtro por Personal Asignado --}}
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="personal_asignado_id">
                Personal Asignado
            </label>
            <select name="personal_asignado_id" id="personal_asignado_id"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 form-select">
                <option value="">Cualquier Personal</option>
                @foreach($personal as $p)
                    <option value="{{ $p->id }}" {{ (request('personal_asignado_id') == $p->id) ? 'selected' : '' }}>
                        {{ $p->nombre }} — {{ $p->documento }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Nuevo: Filtro por Equipo Asignado --}}
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300" for="equipo_asignado_id">
                Equipo Asignado
            </label>
            <select name="equipo_asignado_id" id="equipo_asignado_id"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 form-select">
                <option value="">Cualquier Equipo</option>
                @foreach($equipos as $e)
                    <option value="{{ $e->id }}" {{ (request('equipo_asignado_id') == $e->id) ? 'selected' : '' }}>
                        {{ $e->nombre }} — {{ $e->marca }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

