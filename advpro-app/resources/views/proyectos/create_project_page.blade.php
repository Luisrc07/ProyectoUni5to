<x-layouts.app>
    <div class="p-2"> {{-- Reducido el padding para más espacio --}}
        <div class="max-w-2xl p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 w-full mx-auto"> {{-- Max-width reducido a 2xl y padding a p-4 --}}
            <div class="mb-4"> {{-- Margen inferior reducido --}}
                <div class="flex justify-between items-center mb-4"> {{-- Margen inferior reducido --}}
                    <p class="text-xl font-semibold text-gray-800 dark:text-gray-300">Crear Nuevo Proyecto</p> {{-- Tamaño de texto reducido --}}
                    <a href="{{ route('proyectos.index') }}" class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"> {{-- Tamaño de texto reducido --}}
                        Volver al Panel
                    </a>
                </div>

                <form action="{{ route('proyectos.store') }}" method="POST" id="create-project-form-page">
                    @csrf
                    <div>
                        {{-- Campos básicos del proyecto --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4"> {{-- Gap reducido a 4, margen inferior a 4 --}}
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Nombre del Proyecto</span> {{-- Tamaño de texto reducido --}}
                                <input type="text" name="nombre"
                                    class="block w-full h-9 px-4 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input text-sm" {{-- Altura h-9, px-4, py-2, rounded-md, text-sm --}}
                                    placeholder="Nombre del Proyecto" value="{{ old('nombre', $proyecto->nombre ?? '') }}" required
                                />
                            </label>
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Descripción</span> {{-- Tamaño de texto reducido --}}
                                <textarea name="descripcion"
                                    class="block w-full h-20 px-4 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-textarea text-sm" {{-- Altura h-20, px-4, py-2, rounded-md, text-sm --}}
                                    placeholder="Descripción del proyecto" required>{{ old('descripcion', $proyecto->descripcion ?? '') }}</textarea>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4"> {{-- Gap y margen inferior reducidos --}}
                            {{-- Duración Estimada en Minutos --}}
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Duración Estimada (minutos)</span> {{-- Tamaño de texto reducido --}}
                                <input type="number" name="duracion_estimada_minutos" id="duracion_estimada_minutos" oninput="calculatePresupuesto()"
                                    class="block w-full h-9 px-4 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input text-sm" {{-- Altura h-9, px-4, py-2, rounded-md, text-sm --}}
                                    value="{{ old('duracion_estimada_minutos', $proyecto->duracion_estimada_minutos ?? '') }}" required min="1"
                                />
                                <p id="duracionError" class="text-red-500 text-xs mt-0.5"></p> {{-- Margen superior reducido --}}
                            </label>

                            {{-- Presupuesto (Automático) --}}
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Presupuesto (Automático)</span> {{-- Tamaño de texto reducido --}}
                                <input type="number" name="presupuesto" id="presupuesto" readonly
                                    class="block w-full h-9 px-4 py-2 border border-gray-300 rounded-md bg-gray-100 placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 form-input cursor-not-allowed text-sm" {{-- Altura h-9, px-4, py-2, rounded-md, text-sm --}}
                                    step="0.01" value="{{ old('presupuesto', $proyecto->presupuesto ?? 0) }}" required
                                />
                            </label>
                        </div>

                        {{-- Nuevos campos para Fecha de Inicio Estimada y Fecha de Fin Estimada --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Fecha de Inicio Estimada</span>
                                <input type="date" name="fecha_inicio_estimada"
                                    class="block w-full h-9 px-4 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input text-sm"
                                    value="{{ old('fecha_inicio_estimada', $proyecto->fecha_inicio_estimada ?? '') }}" />
                            </label>
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Fecha de Fin Estimada</span>
                                <input type="date" name="fecha_fin_estimada"
                                    class="block w-full h-9 px-4 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input text-sm"
                                    value="{{ old('fecha_fin_estimada', $proyecto->fecha_fin_estimada ?? '') }}" />
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4"> {{-- Gap y margen inferior reducidos --}}
                            {{-- Estado --}}
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Estado</span> {{-- Tamaño de texto reducido --}}
                                <select name="estado" class="block w-full h-9 px-4 py-2 border border-gray-300 rounded-md focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-select text-sm" required> {{-- Altura h-9, px-4, py-2, rounded-md, text-sm --}}
                                    <option value="" disabled {{ old('estado', $proyecto->estado ?? '') ? '' : 'selected' }}>Seleccione un estado</option>
                                    <option value="En espera" {{ old('estado', $proyecto->estado ?? '') == 'En espera' ? 'selected' : '' }}>En espera</option>
                                    <option value="En proceso" {{ old('estado', $proyecto->estado ?? '') == 'En proceso' ? 'selected' : '' }}>En proceso</option>
                                    <option value="Realizado" {{ old('estado', $proyecto->estado ?? '') == 'Realizado' ? 'selected' : '' }}>Realizado</option>
                                </select>
                            </label>

                            {{-- Lugar --}}
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Lugar (Opcional)</span> {{-- Tamaño de texto reducido --}}
                                <input type="text" name="lugar"
                                    class="block w-full h-9 px-4 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input text-sm" {{-- Altura h-9, px-4, py-2, rounded-md, text-sm --}}
                                    placeholder="Lugar del proyecto" value="{{ old('lugar', $proyecto->lugar ?? '') }}"
                                />
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4"> {{-- Gap y margen inferior reducidos --}}
                            {{-- Responsable --}}
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Responsable</span> {{-- Tamaño de texto reducido --}}
                                <select name="responsable_id"
                                    class="block w-full h-9 px-4 py-2 border border-gray-300 rounded-md focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-select text-sm"> {{-- Altura h-9, px-4, py-2, rounded-md, text-sm --}}
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
                        <div class="mb-4 border border-gray-300 dark:border-gray-600 rounded-lg p-3"> {{-- Margen inferior y padding reducidos --}}
                            <h3 class="text-base font-semibold mb-2 text-gray-700 dark:text-gray-300">Personal Asignado</h3> {{-- Tamaño de texto reducido --}}
                            <div id="personal-container">
                                {{-- Los campos de personal se añadirán aquí dinámicamente con JavaScript --}}
                            </div>
                            <button type="button" onclick="addPersonal()"
                                class="mt-3 px-3 py-1.5 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"> {{-- Padding y tamaño de texto reducidos --}}
                                Añadir Personal
                            </button>
                        </div>

                        {{-- Sección de Equipos Asignados --}}
                        <div class="mb-4 border border-gray-300 dark:border-gray-600 rounded-lg p-3"> {{-- Margen inferior y padding reducidos --}}
                            <h3 class="text-base font-semibold mb-2 text-gray-700 dark:text-gray-300">Equipos Asignados</h3> {{-- Tamaño de texto reducido --}}
                            <div id="equipos-container">
                                {{-- Los campos de equipo se añadirán aquí dinámicamente con JavaScript --}}
                            </div>
                            <button type="button" onclick="addEquipo()"
                                class="mt-3 px-3 py-1.5 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"> {{-- Padding y tamaño de texto reducidos --}}
                                Añadir Equipo
                            </button>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex items-center justify-end mt-4 space-x-3"> {{-- Margen superior y espacio reducidos --}}
                        <a href="{{ route('proyectos.index') }}" class="px-3 py-1.5 text-sm font-medium text-gray-700 transition-colors duration-150 border border-gray-300 rounded-md dark:text-gray-400 hover:border-gray-500 focus:border-gray-500 focus:outline-none focus:shadow-outline-gray"> {{-- Padding y tamaño de texto reducidos --}}
                            Volver
                        </a>
                        <button type="submit" class="px-3 py-1.5 text-sm font-medium text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"> {{-- Padding y tamaño de texto reducidos --}}
                            Guardar Proyecto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const allPersonal = @json($personal);
        const allEquipos = @json($equipos);
        let personalIndex = 0;
        let equipoIndex = 0;

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
            newDiv.className = 'flex flex-wrap items-end gap-3 mb-3 p-2 border border-gray-200 dark:border-gray-700 rounded-md relative'; // Gap, padding y margen reducidos
            newDiv.dataset.index = personalIndex;

            newDiv.innerHTML = `
                <input type="hidden" name="recursos_personal[${personalIndex}][id]" value="${id || ''}">
                <div class="w-full">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-400">Personal:</label> {{-- Tamaño de texto reducido --}}
                    <select name="recursos_personal[${personalIndex}][staff_id]" onchange="calculatePresupuesto(); updateAllSelectOptions();"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 text-sm" required> {{-- Tamaño de texto reducido --}}
                        <option value="" disabled selected>Seleccione personal</option>
                        ${allPersonal.map(p => `<option value="${p.id}" ${p.id == staff_id ? 'selected' : ''}>${p.nombre} — ${p.documento}</option>`).join('')}
                    </select>
                </div>
                <button type="button" onclick="removePersonal(this)"
                    class="absolute top-1 right-1 text-red-500 hover:text-red-700 focus:outline-none"> {{-- Posición ajustada --}}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> {{-- Tamaño del icono reducido --}}
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
            newDiv.className = 'flex flex-wrap items-end gap-3 mb-3 p-2 border border-gray-200 dark:border-gray-700 rounded-md relative'; // Gap, padding y margen reducidos
            newDiv.dataset.index = equipoIndex;

            newDiv.innerHTML = `
                <input type="hidden" name="recursos_equipos[${equipoIndex}][id]" value="${id || ''}">
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-400">Equipo:</label> {{-- Tamaño de texto reducido --}}
                    <select name="recursos_equipos[${equipoIndex}][equipo_id]" onchange="calculatePresupuesto(); updateAllSelectOptions();"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 text-sm" required> {{-- Tamaño de texto reducido --}}
                        <option value="" disabled selected>Seleccione equipo</option>
                        ${allEquipos.map(e => `<option value="${e.id}" ${e.id == equipo_id ? 'selected' : ''}>${e.nombre} (${e.marca})</option>`).join('')}
                    </select>
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-400">Cantidad:</label> {{-- Tamaño de texto reducido --}}
                    <input type="number" name="recursos_equipos[${equipoIndex}][cantidad]" value="${cantidad}" oninput="calculatePresupuesto()"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 text-sm" min="1" required> {{-- Tamaño de texto reducido --}}
                </div>
                <button type="button" onclick="removeEquipo(this)"
                    class="absolute top-1 right-1 text-red-500 hover:text-red-700 focus:outline-none"> {{-- Posición ajustada --}}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> {{-- Tamaño del icono reducido --}}
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
            // Define rates for easier modification (all in USD)
            const BASE_PROJECT_FEE = 150; // Base fee for any project
            const DAILY_PROJECT_OVERHEAD = 50; // Cost per day for general project management/overhead
            const DAILY_PERSONAL_RATE = 150; // Cost per assigned person per day (increased)
            const EQUIPMENT_VALUE_PERCENTAGE = 0.30; // 30% of total equipment value for project usage (increased)

            let totalPresupuesto = 0;
            const duracionEstimadaMinutosInput = document.getElementById('duracion_estimada_minutos');
            const presupuestoInput = document.getElementById('presupuesto');
            const duracionError = document.getElementById('duracionError');

            duracionError.textContent = '';
            let projectDurationMinutes = parseInt(duracionEstimadaMinutosInput.value);

            // Input validation for duration
            if (isNaN(projectDurationMinutes) || projectDurationMinutes <= 0) {
                presupuestoInput.value = (0).toFixed(2);
                duracionError.textContent = 'La duración estimada debe ser un número positivo.';
                return;
            }

            // Convert minutes to hours and days for calculations
            const projectDurationHours = projectDurationMinutes / 60;
            const projectDurationDays = projectDurationHours / 24; // Assuming 24 hours per day for simplicity in daily rates

            // 1. Add Base Project Fee
            totalPresupuesto += BASE_PROJECT_FEE;

            // 2. Add Project Duration Overhead Cost
            if (projectDurationDays > 0) {
                totalPresupuesto += projectDurationDays * DAILY_PROJECT_OVERHEAD;
            }


            // 3. Add Cost for Assigned Personal
            const assignedPersonalElements = document.querySelectorAll('#personal-container select[name$="[staff_id]"]');
            assignedPersonalElements.forEach(selectElement => {
                // We only add cost if a person is selected and duration is valid
                if (selectElement.value && projectDurationDays > 0) {
                    totalPresupuesto += projectDurationDays * DAILY_PERSONAL_RATE;
                }
            });

            // 4. Add Cost for Assigned Equipment (percentage of total value)
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
            totalPresupuesto += totalValorEquipos * EQUIPMENT_VALUE_PERCENTAGE;

            // Update the budget input field, formatted to 2 decimal places
            presupuestoInput.value = totalPresupuesto.toFixed(2);
        }
    </script>
</x-layouts.app>
