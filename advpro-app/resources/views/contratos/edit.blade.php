<x-layouts.app>

<div class="flex items-center justify-center p-4">
  <div class="max-w-md p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <div class="mb-6">
      <p class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
        Modificar Contrato
      </p>
      <form action="{{ url('contratos', ['contrato' => $contrato->id]) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Serial del Contrato (sin cambios) --}}
        <label class="block mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-400">Serial del Contrato</span>
            <input type="text" value="{{ $contrato->serial }}" readonly
                class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 form-input cursor-not-allowed"
            />
        </label>

        {{-- Cliente y Proyecto (sin cambios) --}}
        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Cliente</span>
          <select id="client-select-edit" name="id_cliente" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" {{ !old('id_cliente', $contrato->id_cliente ?? '') ? 'selected' : '' }}>- Seleccione -</option>
            @foreach($clientes as $cliente)
              <option value="{{ $cliente->id }}" {{ (old('id_cliente', $contrato->id_cliente) == $cliente->id) ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
            @endforeach
          </select>
        </label>
        {{-- Mensaje de estado de contratos del cliente --}}
        <p id="client-active-contracts-message-edit" class="mt-1 text-xs"></p>

        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Proyecto</span>
          <select id="project-select-edit" name="id_proyecto" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="" {{ !old('id_proyecto', $contrato->id_proyecto ?? '') ? 'selected' : '' }}>- Seleccione -</option>
            @foreach($proyectos as $proyecto)
              <option value="{{ $proyecto->id }}" {{ (old('id_proyecto', $contrato->id_proyecto) == $proyecto->id) ? 'selected' : '' }}>{{ $proyecto->nombre }}</option>
            @endforeach
          </select>
        </label>

        {{-- Mensaje de estado del proyecto --}}
        <p id="project-status-message-edit" class="mt-1 text-xs"></p>

        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Fecha del Contrato</span>
          <input type="date" name="fecha_contrato"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            value="{{ old('fecha_contrato', $contrato->fecha_contrato ? $contrato->fecha_contrato->format('Y-m-d') : '') }}"
          />
          @error('fecha_contrato')
              <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
          @enderror
        </label>

        {{-- CORREGIDO: Fecha de Entrega --}}
        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Fecha de Entrega</span>
          <input type="date" name="fecha_entrega"
            class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
            value="{{ old('fecha_entrega', $contrato->fecha_entrega ? $contrato->fecha_entrega->format('Y-m-d') : '') }}"
          />
          @error('fecha_entrega')
              <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
          @enderror
        </label>

        {{-- Costo y Estado (sin cambios) --}}
        <label class="block mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-400">Fecha de Inicio del Proyecto</span>
            <input type="date" id="fecha-inicio-proyecto-edit" name="fecha_inicio_proyecto"
                class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                value="{{ old('fecha_inicio_proyecto', $contrato->fecha_inicio_proyecto?->format('Y-m-d')) }}"
            />
        </label>

        <label class="block mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-400">Fecha de Fin del Proyecto</span>
            <input type="date" id="fecha-fin-proyecto-edit" name="fecha_fin_proyecto"
                class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                value="{{ old('fecha_fin_proyecto', $contrato->fecha_fin_proyecto?->format('Y-m-d')) }}"
            />
        </label>

        {{-- Campo de Costo Base (solo lectura) --}}
        <label class="block mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-400">Costo Base del Proyecto</span>
            <input type="number" id="base-cost-display-edit" readonly
                class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input cursor-not-allowed"
                placeholder="0.00"
                step="0.01"
            />
        </label>

        {{-- Campo de Costo Final (se envía como 'costo') --}}
        <label class="block mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-400">Costo Final del Contrato (30% Adicional + Ajuste por Días)</span>
            <input type="number" name="costo" id="final-cost-input-edit" readonly
                class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input cursor-not-allowed"
                step="0.01"
                value="{{ old('costo', $contrato->costo) }}"
            />
        </label>

        <label class="block mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-400">Estado</span>
          <select name="estado" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
            <option value="activo" {{ (old('estado', $contrato->estado) == 'activo') ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ (old('estado', $contrato->estado) == 'inactivo') ? 'selected' : '' }}>Inactivo</option>
            <option value="finalizado" {{ (old('estado', $contrato->estado) == 'finalizado') ? 'selected' : '' }}>Finalizado</option>
            <option value="pendiente" {{ (old('estado', $contrato->estado) == 'pendiente') ? 'selected' : '' }}>Pendiente</option>
          </select>
        </label>

        {{-- Botones (sin cambios) --}}
        <div class="flex items-center justify-end mt-6 space-x-4">
          <a href="{{ route('contratos.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 hover:border-gray-500 focus:border-gray-500 focus:outline-none focus:shadow-outline-gray">
            Volver
          </a>
          <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const projects = {!! $proyectosJson ?? '[]' !!}; // Datos de proyectos desde PHP
        const clients = {!! $clientsJson ?? '[]' !!}; // Datos de clientes desde PHP

        const clientSelect = document.getElementById('client-select-edit');
        const projectSelect = document.getElementById('project-select-edit');
        const fechaInicioInput = document.getElementById('fecha-inicio-proyecto-edit');
        const fechaFinInput = document.getElementById('fecha-fin-proyecto-edit');
        const baseCostDisplay = document.getElementById('base-cost-display-edit');
        const finalCostInput = document.getElementById('final-cost-input-edit');
        const projectStatusMessage = document.getElementById('project-status-message-edit');
        const clientActiveContractsMessage = document.getElementById('client-active-contracts-message-edit');

        const DAILY_RATE_ADJUSTMENT = 10; // $10 por día de duración del proyecto

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

            // Cálculo: (Presupuesto del Proyecto * 1.30) + (Días de Duración * $10/día)
            const final = (baseProjectBudget * 1.30) + (daysDifference * DAILY_RATE_ADJUSTMENT);
            finalCostInput.value = final.toFixed(2);
        }

        function updateProjectAndClientDetails() {
            // Actualizar detalles del proyecto (costo base, estado)
            const selectedProjectId = projectSelect.value;
            const selectedProject = projects.find(p => p.id == selectedProjectId);

            if (selectedProject) {
                if (['en espera', 'en proceso'].includes(selectedProject.estado)) {
                    projectStatusMessage.textContent = `Advertencia: El proyecto "${selectedProject.nombre}" está en estado "${selectedProject.estado}". No se recomienda asignar un contrato.`;
                    projectStatusMessage.classList.add('text-red-500');
                } else {
                    projectStatusMessage.textContent = '';
                    projectStatusMessage.classList.remove('text-red-500');
                }
            } else {
                projectStatusMessage.textContent = '';
                projectStatusMessage.classList.remove('text-red-500');
            }
            
            // Actualizar detalles del cliente (contratos activos)
            const selectedClientId = clientSelect.value;
            const selectedClient = clients.find(c => c.id == selectedClientId);

            // currentContratoId es para excluir el contrato que se está editando de la cuenta de contratos activos
            const currentContratoId = {{ $contrato->id ?? 'null' }}; 

            if (selectedClient && selectedClient.active_contracts_count > 0) {
                clientActiveContractsMessage.textContent = `Advertencia: El cliente "${selectedClient.nombre}" ya tiene un contrato en estado "en proceso" o "en espera".`;
                clientActiveContractsMessage.classList.add('text-red-500');
            } else {
                clientActiveContractsMessage.textContent = '';
                clientActiveContractsMessage.classList.remove('text-red-500');
            }

            calculateFinalCost(); // Recalcular el costo final después de actualizar los detalles
        }

        // Asignar eventos
        clientSelect.addEventListener('change', updateProjectAndClientDetails);
        projectSelect.addEventListener('change', updateProjectAndClientDetails);
        fechaInicioInput.addEventListener('change', calculateFinalCost);
        fechaFinInput.addEventListener('change', calculateFinalCost);

        // Llamar a la función en la carga inicial
        updateProjectAndClientDetails();
    });
</script>
</x-layouts.app>