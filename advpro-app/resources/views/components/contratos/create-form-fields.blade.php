<div class="mt-2 text-sm">
    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Cliente</span>
        <select id="client-select-create" name="id_cliente" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" selected disabled>- Seleccione -</option>
            @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}" {{ old('id_cliente') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
            @endforeach
        </select>
    </label>
    {{-- Mensaje de estado de contratos del cliente --}}
    <p id="client-active-contracts-message-create" class="mt-1 text-xs"></p>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Proyecto</span>
        <select id="project-select-create" name="id_proyecto" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" selected disabled>- Seleccione -</option>
            @foreach($proyectos as $proyecto)
                <option value="{{ $proyecto->id }}" {{ old('id_proyecto') == $proyecto->id ? 'selected' : '' }}>{{ $proyecto->nombre }}</option>
            @endforeach
        </select>
    </label>

    {{-- Mensaje de estado del proyecto --}}
    <p id="project-status-message-create" class="mt-1 text-xs"></p>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Fecha del Contrato</span>
        {{-- ASEGÚRATE DE QUE ESTA LÍNEA SOLO USE old() --}}
        <input type="date" name="fecha_contrato"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            value="{{ old('fecha_contrato') }}"
        />
        @error('fecha_contrato')
            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
        @enderror
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Fecha de Entrega</span>
        {{-- ASEGÚRATE DE QUE ESTA LÍNEA SOLO USE old() --}}
        <input type="date" name="fecha_entrega"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            value="{{ old('fecha_entrega') }}"
        />
        @error('fecha_entrega')
            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
        @enderror
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Fecha de Inicio del Proyecto</span>
        <input type="date" id="fecha-inicio-proyecto-create" name="fecha_inicio_proyecto"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
        />
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Fecha de Fin del Proyecto</span>
        <input type="date" id="fecha-fin-proyecto-create" name="fecha_fin_proyecto"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
        />
    </label>

    {{-- Campo de Costo Base (solo lectura) --}}
    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Costo Base del Proyecto</span>
        <input type="number" id="base-cost-display-create" readonly
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input cursor-not-allowed"
            placeholder="0.00"
            step="0.01"
        />
    </label>

    {{-- Campo de Costo Final (se envía como 'costo') --}}
    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Costo Final del Contrato (30% Adicional + Ajuste por Días)</span>
        <input type="number" name="costo" id="final-cost-input-create" readonly
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input cursor-not-allowed"
            step="0.01"
        />
    </label>

    <label class="block mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-400">Estado</span>
        <select name="estado" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            <option value="finalizado" {{ old('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
            <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
        </select>
    </label>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const projects = {!! $proyectosJson ?? '[]' !!};
        const clients = {!! $clientsJson ?? '[]' !!};

        const clientSelect = document.getElementById('client-select-create');
        const projectSelect = document.getElementById('project-select-create');
        const fechaInicioInput = document.getElementById('fecha-inicio-proyecto-create');
        const fechaFinInput = document.getElementById('fecha-fin-proyecto-create');
        const baseCostDisplay = document.getElementById('base-cost-display-create');
        const finalCostInput = document.getElementById('final-cost-input-create');
        const projectStatusMessage = document.getElementById('project-status-message-create');
        const clientActiveContractsMessage = document.getElementById('client-active-contracts-message-create');

        const DAILY_RATE_ADJUSTMENT = 10;

        function calculateFinalCost() {
            const selectedProjectId = projectSelect.value;
            const selectedProject = projects.find(p => p.id == selectedProjectId);

            let baseProjectBudget = 0;
            if (selectedProject) {
                baseProjectBudget = selectedProject.presupuesto;
            }
            baseCostDisplay.value = baseProjectBudget.toFixed(2);

            let daysDifference = 0;
            const startDate = new Date(fechaInicioInput.value);
            const endDate = new Date(fechaFinInput.value);

            if (startDate && endDate && startDate <= endDate) {
                const diffTime = Math.abs(endDate - startDate);
                daysDifference = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            }

            const final = (baseProjectBudget * 1.30) + (daysDifference * DAILY_RATE_ADJUSTMENT);
            finalCostInput.value = final.toFixed(2);
        }

        function updateProjectAndClientDetails() {
            const selectedProjectId = projectSelect.value;
            const selectedProject = projects.find(p => p.id == selectedProjectId);

            if (selectedProject) {
                //Se mantiene 'en espera' y 'en proceso' para la validación del proyecto
                if (['en espera', 'en proceso'].includes(selectedProject.estado)) {
                    projectStatusMessage.textContent = `Advertencia: El proyecto "${selectedProject.nombre}" está en estado "${selectedProject.estado}". No se recomienda crear un contrato.`;
                    projectStatusMessage.classList.add('text-red-500');
                } else {
                    projectStatusMessage.textContent = '';
                    projectStatusMessage.classList.remove('text-red-500');
                }
            } else {
                projectStatusMessage.textContent = '';
                projectStatusMessage.classList.remove('text-red-500');
            }
            
            const selectedClientId = clientSelect.value;
            const selectedClient = clients.find(c => c.id == selectedClientId);

            //Validación de cliente: se asume 'Activo' y 'Pendiente'
            if (selectedClient && selectedClient.active_contracts_count > 0) {
                clientActiveContractsMessage.textContent = `Advertencia: El cliente "${selectedClient.nombre}" ya tiene un contrato en estado "Activo" o "Pendiente".`; // <--- CAMBIO AQUÍ
                clientActiveContractsMessage.classList.add('text-red-500');
            } else {
                clientActiveContractsMessage.textContent = '';
                clientActiveContractsMessage.classList.remove('text-red-500');
            }

            calculateFinalCost();
        }

        clientSelect.addEventListener('change', updateProjectAndClientDetails);
        projectSelect.addEventListener('change', updateProjectAndClientDetails);
        fechaInicioInput.addEventListener('change', calculateFinalCost);
        fechaFinInput.addEventListener('change', calculateFinalCost);

        updateProjectAndClientDetails();
    });
</script>