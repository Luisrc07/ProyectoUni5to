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
        {{-- Fecha de Inicio --}}
        <label class="block w-full relative">
            <span class="flex items-center mb-2 text-gray-600 text-sm font-medium dark:text-gray-400">Fecha de Inicio</span>
            <input type="date" name="fecha_inicio" id="fecha_inicio" onchange="calculatePresupuesto()"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                value="{{ old('fecha_inicio', $proyecto->fecha_inicio ?? '') }}" required
            />
            <p id="fechaInicioError" class="text-red-500 text-xs mt-1"></p>
        </label>

        {{-- Fecha de Fin --}}
        <label class="block w-full relative">
            <span class="flex items-center mb-2 text-gray-600 text-sm font-medium dark:text-gray-400">Fecha de Fin</span>
            <input type="date" name="fecha_fin" id="fecha_fin" onchange="calculatePresupuesto()"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                value="{{ old('fecha_fin', $proyecto->fecha_fin ?? '') }}"
            />
            <p id="fechaFinError" class="text-red-500 text-xs mt-1"></p>
        </label>
    </div>

    <div class="flex gap-x-6 mb-6">
        {{-- Presupuesto (Automático) --}}
        <label class="block w-full relative">
            <span class="flex items-center mb-2 text-gray-600 text-sm font-medium dark:text-gray-400">Presupuesto (Automático)</span>
            <input type="number" name="presupuesto" id="presupuesto" readonly
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full bg-gray-100 placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 form-input cursor-not-allowed"
                step="0.01" value="{{ old('presupuesto', $proyecto->presupuesto ?? 0) }}" required
            />
        </label>

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
    </div>

    <div class="flex gap-x-6 mb-6">
        {{-- Lugar --}}
        <label class="block w-full relative">
            <span class="flex items-center mb-2 text-gray-600 text-sm font-medium dark:text-gray-400">Lugar (Opcional)</span>
            <input type="text" name="lugar"
                class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                placeholder="Lugar del proyecto" value="{{ old('lugar', $proyecto->lugar ?? '') }}"
            />
        </label>

        {{-- Responsable --}}
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

        calculatePresupuesto(); // Calcular presupuesto inicial
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

    // Función para actualizar las opciones de todos los selectores
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
            <button type="button" x-on:click.stop="removePersonal(this)"
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

    // Modificado: Se eliminan los parámetros de fecha, se tomarán del proyecto
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
            {{-- Las fechas de asignación de equipo se tomarán automáticamente de las fechas del proyecto --}}
            <button type="button" x-on:click.stop="removeEquipo(this)"
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
        let projectDurationDays = 0;

        const fechaInicioInput = document.getElementById('fecha_inicio');
        const fechaFinInput = document.getElementById('fecha_fin');
        const presupuestoInput = document.getElementById('presupuesto');
        const fechaInicioError = document.getElementById('fechaInicioError');
        const fechaFinError = document.getElementById('fechaFinError');

        fechaInicioError.textContent = '';
        fechaFinError.textContent = '';

        const startDate = fechaInicioInput.value ? new Date(fechaInicioInput.value) : null;
        const endDate = fechaFinInput.value ? new Date(fechaFinInput.value) : null;

        if (startDate && endDate) {
            if (startDate > endDate) {
                fechaFinError.textContent = 'La fecha de fin no puede ser anterior a la fecha de inicio.';
                presupuestoInput.value = (0).toFixed(2);
                return;
            }
            const diffTime = Math.abs(endDate - startDate);
            projectDurationDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (projectDurationDays > 31) { // Aproximadamente 1 mes
                fechaFinError.textContent = 'La duración del proyecto no puede ser mayor a 1 mes.';
                presupuestoInput.value = (0).toFixed(2);
                return;
            }
        } else if (startDate && !endDate) {
            projectDurationDays = 0; // No hay fecha fin, duración 0 para cálculo
        } else if (!startDate) {
            // Si no hay fecha de inicio, no se puede calcular la duración
            projectDurationDays = 0;
        }


        // 2. Costo de Equipos (20% del valor total)
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

        // 3. Aumento por Duración del Proyecto (Criterio: $50 por día)
        if (projectDurationDays > 0) {
            totalPresupuesto += projectDurationDays * 50; // $50 por día de duración del proyecto
        }

        // 4. Aumento por Personal Agregado (Criterio: $100 por persona por día)
        const assignedPersonalElements = document.querySelectorAll('#personal-container select[name$="[staff_id]"]');
        assignedPersonalElements.forEach(selectElement => {
            if (selectElement.value && projectDurationDays > 0) {
                totalPresupuesto += projectDurationDays * 100; // $100 por persona por día
            }
        });

        presupuestoInput.value = totalPresupuesto.toFixed(2);
    }
</script>
