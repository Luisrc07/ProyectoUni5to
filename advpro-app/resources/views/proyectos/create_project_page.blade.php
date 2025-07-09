<x-layouts.app>
    <div class="p-2"> {{-- Reduced padding for more space --}}
        <div class="max-w-2xl p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 w-full mx-auto"> {{-- Max-width reduced to 2xl and padding to p-4 --}}
            <div class="mb-4"> {{-- Reduced bottom margin --}}
                <div class="flex justify-between items-center mb-4"> {{-- Reduced bottom margin --}}
                    <p class="text-xl font-semibold text-gray-800 dark:text-gray-300">{{ isset($proyecto) && $proyecto->id ? 'Editar Proyecto' : 'Crear Nuevo Proyecto' }}</p> {{-- Dynamic title --}}
                    <a href="{{ route('proyectos.index') }}" class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"> {{-- Reduced text size --}}
                        Volver al Panel
                    </a>
                </div>

                {{-- Form: dynamic action for create or update --}}
                <form action="{{ isset($proyecto) && $proyecto->id ? route('proyectos.update', $proyecto->id) : route('proyectos.store') }}" method="POST" id="project-form-page">
                    @csrf
                    @if(isset($proyecto) && $proyecto->id)
                        @method('PUT') {{-- PUT method for updates --}}
                    @endif

                    {{-- Validation Errors Section --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Oops! Hubo algunos problemas con tu entrada:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        {{-- Basic project fields --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4"> {{-- Gap reducido a 4, margen inferior a 4 --}}
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Nombre del Proyecto</span> {{-- Tamaño de texto reducido --}}
                                <input type="text" name="nombre"
                                    class="block w-full h-9 px-4 py-2 border {{ $errors->has('nombre') ? 'border-red-500' : 'border-gray-300' }} rounded-md placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input text-sm" {{-- Altura h-9, px-4, py-2, rounded-md, text-sm --}}
                                    placeholder="Nombre del Proyecto" value="{{ old('nombre', $proyecto->nombre ?? '') }}" required
                                />
                                @error('nombre')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Descripción</span> {{-- Tamaño de texto reducido --}}
                                <textarea name="descripcion"
                                    class="block w-full h-20 px-4 py-2 border {{ $errors->has('descripcion') ? 'border-red-500' : 'border-gray-300' }} rounded-md placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-textarea text-sm" {{-- Altura h-20, px-4, py-2, rounded-md, text-sm --}}
                                    placeholder="Descripción del proyecto" required>{{ old('descripcion', $proyecto->descripcion ?? '') }}</textarea>
                                @error('descripcion')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4"> {{-- Gap y margen inferior reducidos --}}
                            {{-- Duración Estimada en Minutos --}}
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Duración Estimada (minutos)</span> {{-- Tamaño de texto reducido --}}
                                <input type="number" name="duracion_estimada_minutos" id="duracion_estimada_minutos" oninput="calculatePresupuesto()"
                                    class="block w-full h-9 px-4 py-2 border {{ $errors->has('duracion_estimada_minutos') ? 'border-red-500' : 'border-gray-300' }} rounded-md placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input text-sm" {{-- Altura h-9, px-4, py-2, rounded-md, text-sm --}}
                                    value="{{ old('duracion_estimada_minutos', $proyecto->duracion_estimada_minutos ?? '') }}" required min="1"
                                />
                                <p id="duracionError" class="text-red-500 text-xs mt-0.5"></p> {{-- Margen superior reducido --}}
                                @error('duracion_estimada_minutos')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </label>

                            {{-- Presupuesto (Automático) --}}
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Presupuesto (Automático)</span> {{-- Tamaño de texto reducido --}}
                                <input type="number" name="presupuesto" id="presupuesto" readonly
                                    class="block w-full h-9 px-4 py-2 border border-gray-300 rounded-md bg-gray-100 placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 form-input cursor-not-allowed text-sm" {{-- Altura h-9, px-4, py-2, rounded-md, text-sm --}}
                                    step="0.01" value="{{ old('presupuesto', $proyecto->presupuesto ?? 0) }}" required
                                />
                                @error('presupuesto')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4"> {{-- Gap y margen inferior reducidos --}}
                            {{-- Estado --}}
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Estado</span> {{-- Tamaño de texto reducido --}}
                                <select name="estado" id="estado_proyecto" class="block w-full h-9 px-4 py-2 border {{ $errors->has('estado') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-select text-sm" required onchange="updateAllSelectOptions()"> {{-- Added onchange to update options --}}
                                    <option value="" disabled {{ old('estado', $proyecto->estado ?? '') ? '' : 'selected' }}>Seleccione un estado</option>
                                    <option value="En espera" {{ old('estado', $proyecto->estado ?? '') == 'En espera' ? 'selected' : '' }}>En espera</option>
                                    <option value="En proceso" {{ old('estado', $proyecto->estado ?? '') == 'En proceso' ? 'selected' : '' }}>En proceso</option>
                                    <option value="Realizado" {{ old('estado', $proyecto->estado ?? '') == 'Realizado' ? 'selected' : '' }}>Realizado</option>
                                    <option value="Finalizado" {{ old('estado', $proyecto->estado ?? '') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option> {{-- Nuevo estado --}}
                                </select>
                                @error('estado')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </label>

                            {{-- Lugar --}}
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Lugar (Opcional)</span> {{-- Tamaño de texto reducido --}}
                                <input type="text" name="lugar"
                                    class="block w-full h-9 px-4 py-2 border {{ $errors->has('lugar') ? 'border-red-500' : 'border-gray-300' }} rounded-md placeholder-gray-400 focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-input text-sm" {{-- Altura h-9, px-4, py-2, rounded-md, text-sm --}}
                                    placeholder="Lugar del proyecto" value="{{ old('lugar', $proyecto->lugar ?? '') }}"
                                />
                                @error('lugar')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4"> {{-- Gap y margen inferior reducidos --}}
                            {{-- Responsable --}}
                            <label class="block relative">
                                <span class="flex items-center mb-1 text-gray-600 text-xs font-medium dark:text-gray-400">Responsable</span> {{-- Tamaño de texto reducido --}}
                                <select name="responsable_id"
                                    class="block w-full h-9 px-4 py-2 border {{ $errors->has('responsable_id') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 dark:text-gray-300 dark:focus:shadow-outline-gray form-select text-sm" required> {{-- Altura h-9, px-4, py-2, rounded-md, text-sm --}}
                                    <option value="" disabled {{ old('responsable_id', $proyecto->responsable_id ?? '') ? '' : 'selected' }}>Seleccione un responsable</option>
                                    @foreach ($personal as $person)
                                        <option value="{{ $person->id }}" {{ old('responsable_id', $proyecto->responsable_id ?? '') == $person->id ? 'selected' : '' }}>
                                            {{ $person->nombre }} — {{ $person->documento }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsable_id')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>

                        {{-- Sección de Personal Asignado --}}
                        <div class="mb-4 border border-gray-300 dark:border-gray-600 rounded-lg p-3"> {{-- Margen inferior y padding reducidos --}}
                            <h3 class="text-base font-semibold mb-2 text-gray-700 dark:text-gray-300">Personal Asignado</h3> {{-- Tamaño de texto reducido --}}
                            <div id="personal-container">
                                {{-- Los campos de personal se añadirán aquí dinámicamente con JavaScript --}}
                                @error('recursos_personal')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                                @foreach ($errors->get('recursos_personal.*.staff_id') as $message)
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message[0] }}</p>
                                @endforeach
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
                                @error('recursos_equipos')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                                @foreach ($errors->get('recursos_equipos.*.equipo_id') as $message)
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message[0] }}</p>
                                @endforeach
                                @foreach ($errors->get('recursos_equipos.*.cantidad') as $message)
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message[0] }}</p>
                                @endforeach
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

        // --- INICIO: Variables que tu controlador Laravel DEBE pasar a esta vista ---
        // Estas variables son cruciales para la lógica de deshabilitación.
        // Asegúrate de que tu controlador las defina y las pase a la vista.
        const assignedPersonalGloballyInProcess = @json($assignedPersonalGloballyInProcess ?? []); // Array de IDs de personal en proyectos 'En proceso'
        const assignedEquiposGloballyInProcess = @json($assignedEquiposGloballyInProcess ?? []);   // Array de IDs de equipos en proyectos 'En proceso'
        const currentProjectStatus = @json($proyecto->estado ?? null); // Estado del proyecto actual ('En proceso', 'En espera', etc.)
        const currentProjectId = @json($proyecto->id ?? null); // ID del proyecto actual (null si es nuevo)
        // --- FIN: Variables que tu controlador Laravel DEBE pasar a esta vista ---

        document.addEventListener('DOMContentLoaded', function() {
            const initialPersonal = @json($proyecto->personalAsignado ?? []);
            const oldPersonal = @json(old('recursos_personal', []));
            const initialEquipos = @json($proyecto->equiposAsignados ?? []);
            const oldEquipos = @json(old('recursos_equipos', []));

            // Cargar personal existente (desde $proyecto)
            initialPersonal.forEach(p => {
                addPersonal(p.pivot.id, p.id);
            });
            // Cargar personal de old() que no esté ya cargado (para errores de validación en nuevas adiciones)
            oldPersonal.forEach(p => {
                // Solo añadir si no tiene un ID de pivot (es decir, es una adición nueva no persistida)
                // y el staff_id no está vacío, y no es un duplicado de un elemento ya cargado de initialPersonal
                const isAlreadyInitial = initialPersonal.some(ip => ip.id === p.staff_id); // Check if staff_id is in initialPersonal
                if (!p.id && p.staff_id && !isAlreadyInitial) {
                    addPersonal(null, p.staff_id);
                }
            });
            // Ajusta el índice buscando la clase 'resource-row'
            personalIndex = document.querySelectorAll('#personal-container > div.resource-row').length;

            // Cargar equipos existentes (desde $proyecto)
            initialEquipos.forEach(e => {
                addEquipo(e.pivot.id, e.id, e.pivot.cantidad);
            });
            // Cargar equipos de old() que no estén ya cargados
            oldEquipos.forEach(e => {
                const isAlreadyInitial = initialEquipos.some(ie => ie.id === e.equipo_id); // Check if equipo_id is in initialEquipos
                if (!e.id && e.equipo_id && e.cantidad && !isAlreadyInitial) {
                    addEquipo(null, e.equipo_id, e.cantidad);
                }
            });
            // Ajusta el índice buscando la clase 'resource-row'
            equipoIndex = document.querySelectorAll('#equipos-container > div.resource-row').length;


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
            const isCurrentProjectInProcess = currentProjectStatus === 'En proceso';

            // Actualizar selectores de personal
            personalSelects.forEach(currentSelect => {
                const selectedPersonalIds = getCurrentlySelectedIds('personal');
                const currentSelectedValue = currentSelect.value; // Guardar el valor actual

                currentSelect.innerHTML = '<option value="" disabled selected>Seleccione personal</option>'; // Resetear opciones

                allPersonal.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.id;
                    option.textContent = `${p.nombre} — ${p.documento}`;

                    let isDisabled = false;

                    // Regla 1: Deshabilitar si ya está seleccionado en otro lugar DENTRO DE ESTE MISMO FORMULARIO
                    if (selectedPersonalIds.has(p.id) && p.id != currentSelectedValue) {
                        isDisabled = true;
                    }

                    // Regla 2: Deshabilitar si el proyecto actual está 'En proceso'
                    // Y el personal está asignado a CUALQUIER otro proyecto 'En proceso'
                    // Y NO es el personal que ya está asignado a ESTE proyecto (para edición)
                    if (isCurrentProjectInProcess && assignedPersonalGloballyInProcess.includes(p.id)) {
                        // Verificar si este personal está asignado al proyecto actual (para edición)
                        const isAssignedToThisProject = initialPersonal.some(ip => ip.id === p.id);
                        if (!isAssignedToThisProject || (isAssignedToThisProject && p.id != currentSelectedValue)) {
                            // Si no está asignado a este proyecto, o si está asignado a este proyecto
                            // pero no es la opción actualmente seleccionada en este dropdown (evita deshabilitar la opción ya elegida)
                            isDisabled = true;
                        }
                    }

                    option.disabled = isDisabled;
                    currentSelect.appendChild(option);
                });
                currentSelect.value = currentSelectedValue; // Restaurar el valor
            });

            // Actualizar selectores de equipo (lógica similar al personal)
            equipoSelects.forEach(currentSelect => {
                const selectedEquipoIds = getCurrentlySelectedIds('equipos');
                const currentSelectedValue = currentSelect.value; // Guardar el valor actual

                currentSelect.innerHTML = '<option value="" disabled selected>Seleccione equipo</option>'; // Resetear opciones

                allEquipos.forEach(e => {
                    const option = document.createElement('option');
                    option.value = e.id;
                    option.textContent = `${e.nombre} (${e.marca}) - Stock: ${e.stock}`; // Mostrar stock

                    let isDisabled = false;

                    // Regla 1: Deshabilitar si ya está seleccionado en otro lugar DENTRO DE ESTE MISMO FORMULARIO
                    if (selectedEquipoIds.has(e.id) && e.id != currentSelectedValue) {
                        isDisabled = true;
                    }

                    // Regla 2: Deshabilitar si el proyecto actual está 'En proceso'
                    // Y el equipo está asignado a CUALQUIER otro proyecto 'En proceso'
                    // Y NO es el equipo que ya está asignado a ESTE proyecto (para edición)
                    if (isCurrentProjectInProcess && assignedEquiposGloballyInProcess.includes(e.id)) {
                         // Verificar si este equipo está asignado al proyecto actual (para edición)
                        const isAssignedToThisProject = initialEquipos.some(ie => ie.id === e.id);
                        if (!isAssignedToThisProject || (isAssignedToThisProject && e.id != currentSelectedValue)) {
                            // Si no está asignado a este proyecto, o si está asignado a este proyecto
                            // pero no es la opción actualmente seleccionada en este dropdown
                            isDisabled = true;
                        }
                    }

                    option.disabled = isDisabled;
                    currentSelect.appendChild(option);
                });
                currentSelect.value = currentSelectedValue; // Restaurar el valor
            });
        }

        function addPersonal(id = null, staff_id = '') {
            const container = document.getElementById('personal-container');
            const newDiv = document.createElement('div');
            // Añadimos la clase 'resource-row' para una selección más precisa al eliminar
            newDiv.className = 'flex items-center gap-3 mb-3 p-2 border border-gray-200 dark:border-gray-700 rounded-md resource-row';
            newDiv.dataset.index = personalIndex;

            newDiv.innerHTML = `
                <input type="hidden" name="recursos_personal[${personalIndex}][id]" value="${id || ''}">
                <div class="flex-grow"> {{-- Este div toma el espacio disponible, empujando la 'x' a la derecha --}}
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-400">Personal:</label>
                    <select name="recursos_personal[${personalIndex}][staff_id]" onchange="calculatePresupuesto(); updateAllSelectOptions();"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 text-sm" required>
                        <option value="" disabled selected>Seleccione personal</option>
                        ${allPersonal.map(p => `<option value="${p.id}" ${p.id == staff_id ? 'selected' : ''}>${p.nombre} — ${p.documento}</option>`).join('')}
                    </select>
                </div>
                <button type="button" onclick="removePersonal(this)"
                    class="flex-shrink-0 px-2 py-1 text-red-600 hover:text-red-800 focus:outline-none flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900 text-lg font-bold leading-none">
                    x
                </button>
            `;
            container.appendChild(newDiv);
            personalIndex++;
            calculatePresupuesto();
            updateAllSelectOptions(); // Actualizar opciones después de añadir
        }

        function removePersonal(button) {
            // 'closest('.resource-row')' encuentra el div padre con la clase 'resource-row' (que es toda la fila) y lo elimina.
            button.closest('.resource-row').remove();
            calculatePresupuesto();
            updateAllSelectOptions(); // Actualizar opciones después de eliminar
        }

        function addEquipo(id = null, equipo_id = '', cantidad = 1) {
            const container = document.getElementById('equipos-container');
            const newDiv = document.createElement('div');
            // Añadimos la clase 'resource-row' para una selección más precisa al eliminar
            newDiv.className = 'flex items-center gap-3 mb-3 p-2 border border-gray-200 dark:border-gray-700 rounded-md resource-row';
            newDiv.dataset.index = equipoIndex;

            newDiv.innerHTML = `
                <input type="hidden" name="recursos_equipos[${equipoIndex}][id]" value="${id || ''}">
                <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-3"> {{-- Este div toma el espacio disponible y organiza sus inputs en una grilla --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-400">Equipo:</label>
                        <select name="recursos_equipos[${equipoIndex}][equipo_id]" onchange="calculatePresupuesto(); updateAllSelectOptions();"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 text-sm" required>
                            <option value="" disabled selected>Seleccione equipo</option>
                            ${allEquipos.map(e => `<option value="${e.id}" ${e.id == equipo_id ? 'selected' : ''}>${e.nombre} (${e.marca}) - Stock: ${e.stock}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-400">Cantidad:</label>
                        <input type="number" name="recursos_equipos[${equipoIndex}][cantidad]" value="${cantidad}" oninput="calculatePresupuesto()"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 text-sm" min="1" required>
                    </div>
                </div>
                <button type="button" onclick="removeEquipo(this)"
                    class="flex-shrink-0 px-2 py-1 text-red-600 hover:text-red-800 focus:outline-none flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900 text-lg font-bold leading-none">
                    x
                </button>
            `;
            container.appendChild(newDiv);
            equipoIndex++;
            calculatePresupuesto();
            updateAllSelectOptions(); // Actualizar opciones después de añadir
        }

        function removeEquipo(button) {
            // 'closest('.resource-row')' encuentra el div padre con la clase 'resource-row' (que es toda la fila) y lo elimina.
            button.closest('.resource-row').remove();
            calculatePresupuesto();
            updateAllSelectOptions(); // Actualizar opciones después de eliminar
        }

        function calculatePresupuesto() {
            // Define rates for easier modification (all in USD)
            const BASE_PROJECT_FEE = 150; // Base fee for any project
            const PER_MINUTE_OVERHEAD_RATE = 5; // Cost per minute for general project overhead (Increased to $5)
            const DAILY_PERSONAL_RATE = 150; // Cost per assigned person per day
            const EQUIPMENT_VALUE_PERCENTAGE = 0.30; // 30% of total equipment value for project usage

            // Max duration: 10 hours in minutes
            const MAX_DURATION_MINUTES = 10 * 60; // 600 minutes

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

            // New validation: Max duration 10 hours (600 minutes)
            if (projectDurationMinutes > MAX_DURATION_MINUTES) {
                duracionError.textContent = `La duración no puede ser mayor a ${MAX_DURATION_MINUTES} minutos (10 horas).`;
                presupuestoInput.value = (0).toFixed(2);
                return;
            }

            // Convert minutes to hours and days for calculations (still needed for personal rate)
            const projectDurationHours = projectDurationMinutes / 60;
            const projectDurationDays = projectDurationHours / 24; // Assuming 24 hours per day for simplicity in daily rates

            // 1. Add Base Project Fee
            totalPresupuesto += BASE_PROJECT_FEE;

            // 2. Add Project Duration Overhead Cost (now per minute)
            totalPresupuesto += projectDurationMinutes * PER_MINUTE_OVERHEAD_RATE;

            // 3. Add Cost for Assigned Personal (still per day)
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
