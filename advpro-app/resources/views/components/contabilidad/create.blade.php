{{-- components/contabilidad/create.blade.php --}}

<div class="p-6">
    <h3 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">Crear Nuevo Asiento Contable</h3>

    {{-- Errores de validación del backend --}}
    @if ($errors->any())
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
            <span class="font-medium">¡Error de validación!</span>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="asiento-form" action="{{ route('contabilidad.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha</label>
                <input type="date" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
            </div>
            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción General</label>
                <input type="text" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" id="descripcion" name="descripcion" value="{{ old('descripcion') }}" required>
            </div>
        </div>

        <hr class="my-6 dark:border-gray-600">

        <h4 class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-200">Detalles del Asiento</h4>
        <div class="overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Cuenta Contable</th>
                        <th class="px-4 py-3">Descripción (Línea)</th>
                        {{-- Aumentado el ancho de las columnas Debe y Haber --}}
                        <th class="px-4 py-3 w-1/4">Debe</th>
                        <th class="px-4 py-3 w-1/4">Haber</th>
                        <th class="px-4 py-3">Acción</th>
                    </tr>
                </thead>
                <tbody id="detalle-asiento-body" class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 text-gray-700 dark:text-gray-400">
                    {{-- Las filas se agregarán dinámicamente aquí --}}
                </tbody>
                <tfoot>
                    <tr class="total-row text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <td colspan="2" class="px-4 py-3 text-right">Totales:</td>
                        <td id="total-debe" class="px-4 py-3 text-right font-mono text-base">0.00</td>
                        <td id="total-haber" class="px-4 py-3 text-right font-mono text-base">0.00</td>
                        <td class="px-4 py-3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <button type="button" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue" id="add-row">Agregar Fila</button>
            <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Guardar Asiento</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('asiento-form');
        const tbody = document.getElementById('detalle-asiento-body');
        const addRowBtn = document.getElementById('add-row');
        const totalDebeEl = document.getElementById('total-debe');
        const totalHaberEl = document.getElementById('total-haber');
        let rowIndex = 0;

        // Se asume que la variable $cuentas está disponible en el scope de esta vista
        const cuentasOptions = @json($cuentas ?? []);

        function createRow() {
            const tr = document.createElement('tr');
            tr.className = "text-gray-700 dark:text-gray-400";
            tr.innerHTML = `
                <td class="px-4 py-3">
                    <select name="detalles[${rowIndex}][id_cuenta]" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-select" required>
                        <option value="">Seleccione una cuenta</option>
                        ${cuentasOptions.map(c => `<option value="${c.id_cuenta}">${c.codigo} - ${c.nombre}</option>`).join('')}
                    </select>
                </td>
                <td class="px-4 py-3">
                    <input type="text" name="detalles[${rowIndex}][descripcion_linea]" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-input">
                </td>
                {{-- Modificado el ancho de los inputs para que sean más grandes --}}
                <td class="px-4 py-3">
                    <input type="number" name="detalles[${rowIndex}][debe]" style="width: 120px;" class="block mt-1 text-sm text-right dark:border-gray-600 dark:bg-gray-700 form-input debe px-2" step="0.01" value="0.00" required>
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="detalles[${rowIndex}][haber]" style="width: 120px;" class="block mt-1 text-sm text-right dark:border-gray-600 dark:bg-gray-700 form-input haber px-2" step="0.01" value="0.00" required>
                </td>
                <td class="px-4 py-3">
                    {{-- Añadido preventDefault directamente en el onclick para evitar el envío del formulario --}}
                    <button type="button" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-red-600 hover:bg-red-700 remove-row">X</button>
                </td>
            `;
            tbody.appendChild(tr);
            rowIndex++;
            updateEventListeners();
        }

        function updateEventListeners() {
            // Eliminar filas
            tbody.querySelectorAll('.remove-row').forEach(btn => {
                // Eliminar listener antiguo para evitar duplicados
                btn.onclick = null;
                btn.onclick = (e) => {
                    e.preventDefault(); // IMPORTANTE: Previene la acción por defecto del botón
                    e.target.closest('tr').remove();
                    calculateTotals();
                };
            });

            // Lógica de inputs (Debe/Haber)
            tbody.querySelectorAll('.debe, .haber').forEach(input => {
                input.oninput = null;
                input.oninput = (e) => {
                    const currentRow = e.target.closest('tr');
                    const debeInput = currentRow.querySelector('.debe');
                    const haberInput = currentRow.querySelector('.haber');

                    // MEJORA: Si se escribe en un campo, el otro se deshabilita y se pone en cero.
                    if (e.target.classList.contains('debe')) {
                        if (parseFloat(e.target.value) > 0) {
                            haberInput.value = '0.00';
                            haberInput.disabled = true;
                        } else {
                            haberInput.disabled = false;
                        }
                    } else if (e.target.classList.contains('haber')) {
                        if (parseFloat(e.target.value) > 0) {
                            debeInput.value = '0.00';
                            debeInput.disabled = true;
                        } else {
                            debeInput.disabled = false;
                        }
                    }
                    calculateTotals();
                };
            });
        }

        function calculateTotals() {
            let totalDebe = 0;
            let totalHaber = 0;
            tbody.querySelectorAll('tr').forEach(tr => {
                // Asegúrate de que los inputs existan antes de acceder a su valor
                const debeInput = tr.querySelector('.debe');
                const haberInput = tr.querySelector('.haber');

                totalDebe += parseFloat(debeInput ? debeInput.value : 0) || 0;
                totalHaber += parseFloat(haberInput ? haberInput.value : 0) || 0;
            });
            totalDebeEl.textContent = totalDebe.toFixed(2);
            totalHaberEl.textContent = totalHaber.toFixed(2);
        }

        addRowBtn.onclick = createRow;

        // MEJORA: Validación en el lado del cliente antes de enviar
        form.addEventListener('submit', function(e) {
            const totalDebe = parseFloat(totalDebeEl.textContent);
            const totalHaber = parseFloat(totalHaberEl.textContent);
            
            if (totalDebe !== totalHaber) {
                e.preventDefault(); // Detiene el envío del formulario
                alert('Error: El total del Debe y el Haber no coinciden.');
            } else if (totalDebe === 0 && totalHaber === 0) {
                e.preventDefault();
                alert('Error: El asiento no puede tener un valor de cero.');
            }
        });

        // Crear dos filas iniciales para empezar
        createRow();
        createRow();
    });
</script>