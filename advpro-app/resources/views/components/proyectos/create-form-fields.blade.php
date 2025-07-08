{{-- resources/views/components/proyectos/create-form-fields.blade.php --}}
@props(['personal', 'equipos', 'proyecto' => null])

<div>
    {{-- Campos básicos del proyecto --}}
    <div class="flex gap-x-6 mb-6">
        <label class="block w-full relative">
            <span class="flex items-center mb-2 text-gray-600 text-sm font-medium dark:text-gray-400">Nombre del Proyecto</span>
            <input type="text" name="nombre"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                placeholder="Nombre del Proyecto" value="{{ old('nombre', $proyecto->nombre ?? '') }}" required
            />
        </label>
        <label class="block w-full relative">
            <span class="flex items-center mb-2 text-gray-600 text-sm font-medium dark:text-gray-400">Descripción</span>
            <textarea name="descripcion"
                class="block w-full h-24 px-5 py-2.5 border border-gray-300 rounded placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-textarea"
                placeholder="Descripción del proyecto" required>{{ old('descripcion', $proyecto->descripcion ?? '') }}</textarea>
        </label>
    </div>

    <div class="flex gap-x-6 mb-6">
        {{-- Duración Estimada (en Minutos) --}}
        <label class="block w-full relative">
            <span class="flex items-center mb-2 text-gray-600 text-sm font-medium dark:text-gray-400">Duración Estimada (Minutos)</span>
            <input type="number" name="duracion_estimada_minutos" id="duracion_estimada_minutos" oninput="calculatePresupuesto()"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                value="{{ old('duracion_estimada_minutos', $proyecto->duracion_estimada_minutos ?? 0) }}" min="0" required
            />
            <p id="duracionError" class="text-red-500 text-xs mt-1"></p>
        </label>

        {{-- Presupuesto (Automático) --}}
        <label class="block w-full relative">
            <span class="flex items-center mb-2 text-gray-600 text-sm font-medium dark:text-gray-400">Presupuesto (Automático)</span>
            <input type="number" name="presupuesto" id="presupuesto" readonly
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full bg-gray-100 placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 form-input cursor-not-allowed"
                step="0.01" value="{{ old('presupuesto', $proyecto->presupuesto ?? 0) }}" required
            />
        </label>
    </div>

    <div class="flex gap-x-6 mb-6">
        {{-- Estado --}}
        <label class="block w-full relative">
            <span class="flex items-center mb-2 text-gray-600 text-sm font-medium dark:text-gray-400">Estado</span>
            <select name="estado" class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-select" required>
                <option value="" disabled {{ old('estado', $proyecto->estado ?? '') ? '' : 'selected' }}>Seleccione un estado</option>
                <option value="En espera" {{ old('estado', $proyecto->estado ?? '') == 'En espera' ? 'selected' : '' }}>En espera</option>
                <option value="En proceso" {{ old('estado', $proyecto->estado ?? '') == 'En proceso' ? 'selected' : '' }}>En proceso</option>
                <option value="Realizado" {{ old('estado', $proyecto->estado ?? '') == 'Realizado' ? 'selected' : '' }}>Realizado</option>
            </select>
        </label>

        {{-- Lugar --}}
        <label class="block w-full relative">
            <span class="flex items-center mb-2 text-gray-600 text-sm font-medium dark:text-gray-400">Lugar (Opcional)</span>
            <input type="text" name="lugar"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                placeholder="Lugar del proyecto" value="{{ old('lugar', $proyecto->lugar ?? '') }}"
            />
        </label>
    </div>

    {{-- Responsable --}}
    <div class="mb-6">
        <label class="block w-full relative">
            <span class="flex items-center mb-2 text-gray-600 text-sm font-medium dark:text-gray-400">Responsable</span>
            <select name="responsable_id"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-select">
                <option value="" disabled {{ old('responsable_id', $proyecto->responsable_id ?? '') ? '' : 'selected' }}>Seleccione un responsable</option>
                @foreach ($personal as $person)
                    <option value="{{ $person->id }}" {{ old('responsable_id', $proyecto->responsable_id ?? '') == $person->id ? 'selected' : '' }}>
                        {{ $person->nombre }} — {{ $person->documento }}
                    </option>
                @endforeach
            </select>
        </label>
    </div>


    {{-- Sección de Personal Asignado --}}
    <div class="mb-6 border border-gray-300 dark:border-gray-600 rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Personal Asignado</h3>
        <div id="personal-container">
            {{-- Los campos de personal se añadirán aquí dinámicamente con JavaScript --}}
        </div>
        <button type="button" onclick="addPersonal()"
            class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Añadir Personal
        </button>
    </div>

    {{-- Sección de Equipos Asignados --}}
    <div class="mb-6 border border-gray-300 dark:border-gray-600 rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Equipos Asignados</h3>
        <div id="equipos-container">
            {{-- Los campos de equipo se añadirán aquí dinámicamente con JavaScript --}}
        </div>
        <button type="button" onclick="addEquipo()"
            class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Añadir Equipo
        </button>
    </div>
</div>

<script>
    const allPersonal = @json($personal);
    const allEquipos = @json($equipos);
    let personalIndex = 0;
    let equipoIndex = 0;

    // Función para inicializar los campos existentes en modo edición
    document.addEventListener('DOMContentLoaded', function() {
        const initialPersonal = @json($proyecto->personalAsignado ?? []);
        const initialEquipos = @json($proyecto->equiposAsignados ?? []);

        initialPersonal.forEach(p => {
            addPersonal(p.pivot.id, p.id);
        });

        initialEquipos.forEach(e => {
            addEquipo(e.pivot.id, e.id, e.pivot.cantidad);
        });

        // Asegurarse de que personalIndex y equipoIndex sean mayores que los índices de los elementos ya cargados
        // Esto evita que nuevos elementos sobrescriban los IDs existentes al usar `addPersonal()` o `addEquipo()`
        // en un formulario de edición.
        const maxPersonalIndex = initialPersonal.reduce((max, item) => Math.max(max, item.pivot.id), -1);
        const maxEquipoIndex = initialEquipos.reduce((max, item) => Math.max(max, item.pivot.id), -1);
        personalIndex = Math.max(personalIndex, maxPersonalIndex + 1);
        equipoIndex = Math.max(equipoIndex, maxEquipoIndex + 1);


        calculatePresupuesto(); // Calcular presupuesto inicial con la duración existente
        updateAllSelectOptions(); // Actualizar opciones de select al cargar
    });

    // Función para obtener los IDs seleccionados actualmente en todos los selectores
    function getCurrentlySelectedIds(type) {
        const ids = new Set();
        const selects = document.querySelectorAll(`#${type}-container select[name$="[${type === 'personal' ? 'staff_id' : 'equipo_id'}]"]`);
        selects.forEach(select => {
            if (select.value) {
                ids.add(parseInt(select.value));
            }
        });
        return ids;
    }

    // Función para actualizar las opciones de todos los selectores (deshabilitando los ya seleccionados)
    function updateAllSelectOptions() {
        const personalSelects = document.querySelectorAll('#personal-container select[name$="[staff_id]"]');
        const equipoSelects = document.querySelectorAll('#equipos-container select[name$="[equipo_id]"]');

        // Actualizar selectores de personal
        personalSelects.forEach(currentSelect => {
            const selectedPersonalIds = getCurrentlySelectedIds('personal');
            const currentSelectedValue = currentSelect.value; // Guardar el valor actual

            currentSelect.innerHTML = '<option value="" disabled selected>Seleccione personal</option>'; // Resetear opciones

            allPersonal.forEach(p => {
                const option = document.createElement('option');
                option.value = p.id;
                option.textContent = `${p.nombre} — ${p.documento}`;
                // Deshabilitar si ya está seleccionado en otro lugar y no es la opción actual
                if (selectedPersonalIds.has(p.id) && p.id != currentSelectedValue) {
                    option.disabled = true;
                }
                currentSelect.appendChild(option);
            });
            currentSelect.value = currentSelectedValue; // Restaurar el valor
        });

        // Actualizar selectores de equipo
        equipoSelects.forEach(currentSelect => {
            const selectedEquipoIds = getCurrentlySelectedIds('equipos');
            const currentSelectedValue = currentSelect.value; // Guardar el valor actual

            currentSelect.innerHTML = '<option value="" disabled selected>Seleccione equipo</option>'; // Resetear opciones

            allEquipos.forEach(e => {
                const option = document.createElement('option');
                option.value = e.id;
                option.textContent = `${e.nombre} (${e.marca})`;
                // Deshabilitar si ya está seleccionado en otro lugar y no es la opción actual
                if (selectedEquipoIds.has(e.id) && e.id != currentSelectedValue) {
                    option.disabled = true;
                }
                currentSelect.appendChild(option);
            });
            currentSelect.value = currentSelectedValue; // Restaurar el valor
        });
    }

    function addPersonal(id = null, staff_id = '') {
        const container = document.getElementById('personal-container');
        const newDiv = document.createElement('div');
        newDiv.className = 'flex flex-wrap items-end gap-4 mb-4 p-3 border border-gray-200 dark:border-gray-700 rounded-md relative';
        newDiv.dataset.index = personalIndex;

        newDiv.innerHTML = `
            <input type="hidden" name="recursos_personal[${personalIndex}][id]" value="${id || ''}">
            <div class="w-full">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Personal:</label>
                <select name="recursos_personal[${personalIndex}][staff_id]" onchange="calculatePresupuesto(); updateAllSelectOptions();"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" required>
                    <option value="" disabled selected>Seleccione personal</option>
                    ${allPersonal.map(p => `<option value="${p.id}" ${p.id == staff_id ? 'selected' : ''}>${p.nombre} — ${p.documento}</option>`).join('')}
                </select>
            </div>
            <button type="button" onclick="removePersonal(this)"
                class="absolute top-2 right-2 text-red-500 hover:text-red-700 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        `;
        container.appendChild(newDiv);
        personalIndex++;
        calculatePresupuesto();
        updateAllSelectOptions(); // Actualizar opciones después de añadir
    }

    function removePersonal(button) {
        button.closest('.flex.flex-wrap').remove();
        calculatePresupuesto();
        updateAllSelectOptions(); // Actualizar opciones después de eliminar
    }

    function addEquipo(id = null, equipo_id = '', cantidad = 1) {
        const container = document.getElementById('equipos-container');
        const newDiv = document.createElement('div');
        newDiv.className = 'flex flex-wrap items-end gap-4 mb-4 p-3 border border-gray-200 dark:border-gray-700 rounded-md relative';
        newDiv.dataset.index = equipoIndex;

        newDiv.innerHTML = `
            <input type="hidden" name="recursos_equipos[${equipoIndex}][id]" value="${id || ''}">
            <div class="w-full md:w-1/2 lg:w-1/3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Equipo:</label>
                <select name="recursos_equipos[${equipoIndex}][equipo_id]" onchange="calculatePresupuesto(); updateAllSelectOptions();"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" required>
                    <option value="" disabled selected>Seleccione equipo</option>
                    ${allEquipos.map(e => `<option value="${e.id}" ${e.id == equipo_id ? 'selected' : ''}>${e.nombre} (${e.marca})</option>`).join('')}
                </select>
            </div>
            <div class="w-full md:w-1/2 lg:w-1/3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Cantidad:</label>
                <input type="number" name="recursos_equipos[${equipoIndex}][cantidad]" value="${cantidad}" oninput="calculatePresupuesto()"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" min="1" required>
            </div>
            <button type="button" onclick="removeEquipo(this)"
                class="absolute top-2 right-2 text-red-500 hover:text-red-700 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        `;
        container.appendChild(newDiv);
        equipoIndex++;
        calculatePresupuesto();
        updateAllSelectOptions(); // Actualizar opciones después de añadir
    }

    function removeEquipo(button) {
        button.closest('.flex.flex-wrap').remove();
        calculatePresupuesto();
        updateAllSelectOptions(); // Actualizar opciones después de eliminar
    }

    function calculatePresupuesto() {
        let totalPresupuesto = 0;
        const MINUTES_IN_DAY = 24 * 60; // 1440 minutos en un día
        const MAX_DURATION_MINUTES = 31 * MINUTES_IN_DAY; // Duración máxima de 31 días en minutos

        const duracionInput = document.getElementById('duracion_estimada_minutos');
        const presupuestoInput = document.getElementById('presupuesto');
        const duracionError = document.getElementById('duracionError');

        duracionError.textContent = ''; // Limpiar mensajes de error previos

        let duracionMinutos = parseFloat(duracionInput.value) || 0;

        // Validación de duración
        if (duracionMinutos < 0) {
            duracionMinutos = 0; // Asegura que no sea negativo
            duracionInput.value = 0; // Resetea el valor en el input si es negativo
        }
        if (duracionMinutos > MAX_DURATION_MINUTES) {
            duracionError.textContent = `La duración no puede ser mayor a ${MAX_DURATION_MINUTES} minutos (aproximadamente 31 días).`;
            presupuestoInput.value = (0).toFixed(2);
            return;
        }

        const projectDurationDays = duracionMinutos / MINUTES_IN_DAY; // Convertir minutos a días para el cálculo

        // 1. Costo de Equipos (20% del valor total)
        let totalValorEquipos = 0;
        const assignedEquiposElements = document.querySelectorAll('#equipos-container select[name$="[equipo_id]"]');
        assignedEquiposElements.forEach(selectElement => {
            const index = selectElement.name.match(/\[(\d+)\]/)[1];
            const equipoId = selectElement.value;
            const cantidadInput = document.querySelector(`input[name="recursos_equipos[${index}][cantidad]"]`);
            const cantidad = parseInt(cantidadInput ? cantidadInput.value : 0);

            const equipo = allEquipos.find(e => e.id == equipoId);
            if (equipo && cantidad > 0) {
                totalValorEquipos += parseFloat(equipo.valor) * cantidad;
            }
        });
        totalPresupuesto += totalValorEquipos * 0.20; // 20% del valor total de los equipos

        // 2. Aumento por Duración del Proyecto (Criterio: $50 por día)
        if (projectDurationDays > 0) {
            totalPresupuesto += projectDurationDays * 50; // $50 por día de duración del proyecto
        }

        // 3. Aumento por Personal Agregado (Criterio: $100 por persona por día)
        const assignedPersonalElements = document.querySelectorAll('#personal-container select[name$="[staff_id]"]');
        assignedPersonalElements.forEach(selectElement => {
            if (selectElement.value && projectDurationDays > 0) {
                totalPresupuesto += projectDurationDays * 100; // $100 por persona por día
            }
        });

        presupuestoInput.value = totalPresupuesto.toFixed(2);
    }
</script>