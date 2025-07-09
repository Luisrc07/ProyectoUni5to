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

    {{-- Mensaje de Alerta para el asiento de Constitución --}}
    @if (!$asientoConstitucionExiste)
        <div class="p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
            <span class="font-medium">¡Atención!</span> Este es el PRIMER asiento contable de la empresa. Por favor, registra el asiento de *"Constitucion"* para iniciar el historial contable.
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
                <input type="text"
                       class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                       id="descripcion"
                       name="descripcion"
                       value="{{ old('descripcion', $asientoConstitucionExiste ? '' : 'Constitucion') }}"
                       {{ $asientoConstitucionExiste ? '' : 'readonly' }}
                       required>
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
                        <td class="px-4 py-3 text-right font-mono text-base">
                            <span id="total-debe">0.00</span>
                            <button type="button" id="btn-iva-debe" class="ml-2 px-2 py-1 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-md active:bg-blue-600 hover:bg-blue-700">Agregar IVA</button>
                        </td>
                        <td class="px-4 py-3 text-right font-mono text-base">
                            <span id="total-haber">0.00</span>
                            <button type="button" id="btn-iva-haber" class="ml-2 px-2 py-1 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-md active:bg-blue-600 hover:bg-blue-700">Agregar IVA</button>
                        </td>
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
        const descripcionInput = document.getElementById('descripcion');
        const btnIvaDebe = document.getElementById('btn-iva-debe');
        const btnIvaHaber = document.getElementById('btn-iva-haber');

        let rowIndex = 0;
        let isIvaApplied = false;
        let ivaAppliedToColumn = ''; // 'debe' or 'haber'
        let ivaRowIndex = -1; // To keep track of the IVA row
        let originalRowValues = []; // To store original values before IVA application

        const cuentasOptions = @json($cuentas ?? []);
        const asientoConstitucionExists = @json($asientoConstitucionExiste ?? false);

        // Find IVA account IDs
        const ivaDebitoFiscalAccount = cuentasOptions.find(c => c.codigo === '210301');
        const ivaCreditoFiscalAccount = cuentasOptions.find(c => c.codigo === '11051');

        if (!ivaDebitoFiscalAccount || !ivaCreditoFiscalAccount) {
            console.error('Error: Cuentas de IVA (210301 o 11051) no encontradas en la lista de cuentas.');
            // Optionally disable IVA buttons or show a message
        }

        function createRow(isIvaRow = false, accountId = null, amount = 0, column = '') {
            const tr = document.createElement('tr');
            tr.className = "text-gray-700 dark:text-gray-400" + (isIvaRow ? ' iva-row' : '');
            let selectHtml = '';
            let descriptionValue = isIvaRow ? 'IVA generado automáticamente' : '';
            let descriptionReadonly = isIvaRow ? 'readonly' : '';
            let descriptionClass = isIvaRow ? 'bg-gray-100 cursor-not-allowed' : '';

            if (isIvaRow) {
                const selectedAccount = cuentasOptions.find(c => c.id_cuenta === accountId);
                const accountDisplayText = selectedAccount ? `${selectedAccount.codigo} - ${selectedAccount.nombre}` : 'Cuenta IVA';
                selectHtml = `
                    <input type="hidden" name="detalles[${rowIndex}][id_cuenta]" value="${accountId}">
                    <span class="block w-full mt-1 text-sm dark:text-gray-300 px-3 py-2 border rounded-md dark:border-gray-600 dark:bg-gray-700 bg-gray-100 cursor-not-allowed">${accountDisplayText}</span>
                `;
            } else {
                // Para filas regulares, inicialmente un select
                selectHtml = `
                    <select name="detalles[${rowIndex}][id_cuenta]" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-select" required>
                        <option value="">Seleccione una cuenta</option>
                        ${cuentasOptions.map(c => `<option value="${c.id_cuenta}" ${accountId === c.id_cuenta ? 'selected' : ''}>${c.codigo} - ${c.nombre}</option>`).join('')}
                    </select>
                `;
            }

            tr.innerHTML = `
                <td class="px-4 py-3">
                    ${selectHtml}
                </td>
                <td class="px-4 py-3">
                    <input type="text" name="detalles[${rowIndex}][descripcion_linea]" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-input ${descriptionClass}" value="${descriptionValue}" ${descriptionReadonly}>
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="detalles[${rowIndex}][debe]" style="width: 120px;" class="block mt-1 text-sm text-right dark:border-gray-600 dark:bg-gray-700 form-input debe px-2 ${isIvaRow || isIvaApplied ? 'bg-gray-100 cursor-not-allowed' : ''}" step="0.01" value="${column === 'debe' ? amount.toFixed(2) : '0.00'}" ${isIvaRow || isIvaApplied ? 'readonly' : ''} required>
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="detalles[${rowIndex}][haber]" style="width: 120px;" class="block mt-1 text-sm text-right dark:border-gray-600 dark:bg-gray-700 form-input haber px-2 ${isIvaRow || isIvaApplied ? 'bg-gray-100 cursor-not-allowed' : ''}" step="0.01" value="${column === 'haber' ? amount.toFixed(2) : '0.00'}" ${isIvaRow || isIvaApplied ? 'readonly' : ''} required>
                </td>
                <td class="px-4 py-3">
                    <button type="button" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-red-600 hover:bg-red-700 remove-row" ${isIvaRow ? 'disabled' : ''}>X</button>
                </td>
            `;
            tbody.appendChild(tr);
            if (isIvaRow) {
                ivaRowIndex = rowIndex;
            }
            rowIndex++;
            updateEventListeners();
            return tr;
        }

        function updateEventListeners() {
            tbody.querySelectorAll('.remove-row').forEach(btn => {
                btn.onclick = null; // Remove previous listeners
                btn.onclick = (e) => {
                    e.preventDefault();
                    if (isIvaApplied) {
                        alert('No se pueden eliminar filas mientras el IVA está aplicado.');
                        return;
                    }
                    e.target.closest('tr').remove();
                    calculateTotals();
                    reindexRows(); // Re-index after row removal
                };
            });

            tbody.querySelectorAll('.debe, .haber').forEach(input => {
                input.oninput = null; // Remove previous listeners
                input.oninput = (e) => {
                    const currentRow = e.target.closest('tr');
                    const debeInput = currentRow.querySelector('.debe');
                    const haberInput = currentRow.querySelector('.haber');

                    if (e.target.classList.contains('debe')) {
                        if (parseFloat(e.target.value) > 0) {
                            haberInput.value = '0.00';
                            haberInput.readOnly = true; // Use readOnly
                            haberInput.classList.add('bg-gray-100', 'cursor-not-allowed'); // Add visual style
                        } else {
                            haberInput.readOnly = false; // Use readOnly
                            haberInput.classList.remove('bg-gray-100', 'cursor-not-allowed'); // Remove visual style
                        }
                    } else if (e.target.classList.contains('haber')) {
                        if (parseFloat(e.target.value) > 0) {
                            debeInput.value = '0.00';
                            debeInput.readOnly = true; // Use readOnly
                            debeInput.classList.add('bg-gray-100', 'cursor-not-allowed'); // Add visual style
                        } else {
                            debeInput.readOnly = false; // Use readOnly
                            debeInput.classList.remove('bg-gray-100', 'cursor-not-allowed'); // Remove visual style
                        }
                    }
                    calculateTotals();
                };
            });
        }

        function calculateTotals() {
            let totalDebe = 0;
            let totalHaber = 0;
            tbody.querySelectorAll('tr').forEach((tr, index) => {
                const debeInput = tr.querySelector('.debe');
                const haberInput = tr.querySelector('.haber');

                // Sum all inputs, regardless of readOnly state, as they will be sent
                totalDebe += parseFloat(debeInput ? debeInput.value : 0) || 0;
                totalHaber += parseFloat(haberInput ? haberInput.value : 0) || 0;
            });
            totalDebeEl.textContent = totalDebe.toFixed(2);
            totalHaberEl.textContent = totalHaber.toFixed(2);
        }

        function applyIva(column) {
            if (isIvaApplied) return;

            const currentTotalDebe = parseFloat(totalDebeEl.textContent);
            const currentTotalHaber = parseFloat(totalHaberEl.textContent);

            if (currentTotalDebe === 0 && currentTotalHaber === 0) {
                alert('No se puede aplicar IVA a un asiento con montos en cero.');
                return;
            }
            if (currentTotalDebe !== currentTotalHaber) {
                alert('Los totales del Debe y Haber deben coincidir antes de aplicar IVA.');
                return;
            }

            isIvaApplied = true;
            ivaAppliedToColumn = column;
            originalRowValues = [];

            tbody.querySelectorAll('tr').forEach((tr, index) => {
                const debeInput = tr.querySelector('.debe');
                const haberInput = tr.querySelector('.haber');
                const selectAccountElement = tr.querySelector('select[name$="[id_cuenta]"]');
                const descLinea = tr.querySelector('input[name$="[descripcion_linea]"]');
                const removeBtn = tr.querySelector('.remove-row');

                // Almacenar valores y estados originales
                originalRowValues.push({
                    debe: parseFloat(debeInput.value) || 0,
                    haber: parseFloat(haberInput.value) || 0,
                    debeReadonly: debeInput.readOnly,
                    haberReadonly: haberInput.readOnly,
                    selectAccountValue: selectAccountElement ? selectAccountElement.value : tr.querySelector('input[name$="[id_cuenta]"][type="hidden"]') ? tr.querySelector('input[name$="[id_cuenta]"][type="hidden"]').value : '', // Obtener valor de select o hidden input
                    descLineaValue: descLinea.value,
                    descLineaReadonly: descLinea.readOnly,
                    removeBtnDisabled: removeBtn.disabled
                });

                // Hacer los campos de entrada de solo lectura y añadir estilos
                debeInput.readOnly = true;
                haberInput.readOnly = true;
                debeInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                haberInput.classList.add('bg-gray-100', 'cursor-not-allowed');

                // Reemplazar select con hidden input y span para mostrar
                if (selectAccountElement) {
                    const selectedAccountId = selectAccountElement.value;
                    const selectedAccount = cuentasOptions.find(c => c.id_cuenta == selectedAccountId); // Usar == para comparar, ya que selectedAccountId podría ser string
                    const accountDisplayText = selectedAccount ? `${selectedAccount.codigo} - ${selectedAccount.nombre}` : 'Cuenta no seleccionada';

                    const parentTd = selectAccountElement.parentNode;
                    parentTd.innerHTML = `
                        <input type="hidden" name="detalles[${index}][id_cuenta]" value="${selectedAccountId}">
                        <span class="block w-full mt-1 text-sm dark:text-gray-300 px-3 py-2 border rounded-md dark:border-gray-600 dark:bg-gray-700 bg-gray-100 cursor-not-allowed">${accountDisplayText}</span>
                    `;
                }
                
                descLinea.readOnly = true;
                descLinea.classList.add('bg-gray-100', 'cursor-not-allowed');
                removeBtn.disabled = true;
            });

            const ivaRate = 0.16;
            let ivaAmount = 0;
            let ivaAccountId = null;
            let ivaColumn = '';
            let oppositeColumn = '';

            if (column === 'debe') {
                ivaAmount = currentTotalHaber * ivaRate;
                ivaAccountId = ivaCreditoFiscalAccount.id_cuenta;
                ivaColumn = 'debe';
                oppositeColumn = 'haber';
            } else {
                ivaAmount = currentTotalDebe * ivaRate;
                ivaAccountId = ivaDebitoFiscalAccount.id_cuenta;
                ivaColumn = 'haber';
                oppositeColumn = 'debe';
            }

            // Añadir nueva fila de IVA al final
            const ivaRow = createRow(true, ivaAccountId, ivaAmount, ivaColumn);
            // Asegurarse de que los campos de entrada de la fila de IVA sean de solo lectura y no se puedan eliminar
            ivaRow.querySelector('.debe').readOnly = true;
            ivaRow.querySelector('.haber').readOnly = true;
            ivaRow.querySelector('input[name$="[descripcion_linea]"]').readOnly = true;
            ivaRow.querySelector('.remove-row').disabled = true;

            // Ajustar montos en la columna opuesta para las filas existentes
            // Es importante iterar sobre las filas reales en el tbody de nuevo
            // para asegurar que obtenemos el orden correcto y evitar problemas de desajustes de índice en originalRowValues
            tbody.querySelectorAll('tr').forEach((tr, trIndex) => {
                // Omitir la fila de IVA recién añadida (tiene la clase 'iva-row')
                if (tr.classList.contains('iva-row')) return;

                const debeInput = tr.querySelector('.debe');
                const haberInput = tr.querySelector('.haber');

                // Solo ajustar si el monto en la columna opuesta es mayor que 0
                if (oppositeColumn === 'debe' && parseFloat(debeInput.value) > 0) {
                    debeInput.value = (originalRowValues[trIndex].debe * (1 + ivaRate)).toFixed(2); // Usar valor original
                } else if (oppositeColumn === 'haber' && parseFloat(haberInput.value) > 0) {
                    haberInput.value = (originalRowValues[trIndex].haber * (1 + ivaRate)).toFixed(2); // Usar valor original
                }
            });

            btnIvaDebe.textContent = 'Quitar IVA';
            btnIvaHaber.textContent = 'Quitar IVA';
            btnIvaDebe.classList.remove('bg-blue-500', 'hover:bg-blue-700');
            btnIvaDebe.classList.add('bg-red-500', 'hover:bg-red-700');
            btnIvaHaber.classList.remove('bg-blue-500', 'hover:bg-blue-700');
            btnIvaHaber.classList.add('bg-red-500', 'hover:bg-red-700');

            addRowBtn.disabled = true;

            calculateTotals();
            // Re-indexar los nombres de las filas después de añadir la fila de IVA para asegurar una correcta submission
            reindexRows();
        }

        function removeIva() {
            if (!isIvaApplied) return;

            isIvaApplied = false;
            ivaAppliedToColumn = '';

            const ivaRowElement = tbody.querySelector('.iva-row');
            if (ivaRowElement) {
                ivaRowElement.remove();
            }
            ivaRowIndex = -1;

            tbody.querySelectorAll('tr').forEach((tr, index) => {
                const debeInput = tr.querySelector('.debe');
                const haberInput = tr.querySelector('.haber');
                // Recuperar el TD padre para el elemento select
                const selectParentTd = tr.querySelector('td:first-child');
                const hiddenAccountIdInput = tr.querySelector('input[name$="[id_cuenta]"][type="hidden"]'); // Encontrar el input oculto
                const spanDisplay = tr.querySelector('span.block.w-full.mt-1'); // Encontrar el span de visualización
                const descLinea = tr.querySelector('input[name$="[descripcion_linea]"]');
                const removeBtn = tr.querySelector('.remove-row');

                if (originalRowValues[index]) {
                    debeInput.value = originalRowValues[index].debe.toFixed(2);
                    haberInput.value = originalRowValues[index].haber.toFixed(2);
                    
                    debeInput.readOnly = originalRowValues[index].debeReadonly;
                    haberInput.readOnly = originalRowValues[index].haberReadonly;
                    if (debeInput.readOnly) {
                        debeInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                    } else {
                        debeInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    }
                    if (haberInput.readOnly) {
                        haberInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                    } else {
                        haberInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    }

                    // Revertir de hidden input + span a select
                    if (selectParentTd && hiddenAccountIdInput && spanDisplay) {
                        const originalSelectedAccountId = originalRowValues[index].selectAccountValue;
                        selectParentTd.innerHTML = `
                            <select name="detalles[${index}][id_cuenta]" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-select" required>
                                <option value="">Seleccione una cuenta</option>
                                ${cuentasOptions.map(c => `<option value="${c.id_cuenta}" ${originalSelectedAccountId == c.id_cuenta ? 'selected' : ''}>${c.codigo} - ${c.nombre}</option>`).join('')}
                            </select>
                        `;
                    }

                    descLinea.value = originalRowValues[index].descLineaValue; // Restaurar descripción original
                    descLinea.readOnly = originalRowValues[index].descLineaReadonly;
                    if (descLinea.readOnly) {
                        descLinea.classList.add('bg-gray-100', 'cursor-not-allowed');
                    } else {
                        descLinea.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    }
                    removeBtn.disabled = originalRowValues[index].removeBtnDisabled;
                }
            });

            btnIvaDebe.textContent = 'Agregar IVA';
            btnIvaHaber.textContent = 'Agregar IVA';
            btnIvaDebe.classList.remove('bg-red-500', 'hover:bg-red-700');
            btnIvaDebe.classList.add('bg-blue-500', 'hover:bg-blue-700');
            btnIvaHaber.classList.remove('bg-red-500', 'hover:bg-red-700');
            btnIvaHaber.classList.add('bg-blue-500', 'hover:bg-blue-700');

            addRowBtn.disabled = false;

            calculateTotals();
            updateEventListeners(); // Volver a adjuntar los event listeners a los selects recién creados
            reindexRows(); // Re-indexar después de eliminar la fila de IVA
        }

        btnIvaDebe.addEventListener('click', function() {
            if (isIvaApplied) {
                removeIva();
            } else {
                applyIva('debe');
            }
        });

        btnIvaHaber.addEventListener('click', function() {
            if (isIvaApplied) {
                removeIva();
            } else {
                applyIva('haber');
            }
        });

        addRowBtn.onclick = function() {
            if (isIvaApplied) {
                alert('No se pueden agregar filas mientras el IVA está aplicado.');
                return;
            }
            createRow();
        };

        // Nueva función para re-indexar los nombres de las filas después de añadir/eliminar filas
        function reindexRows() {
            tbody.querySelectorAll('tr').forEach((tr, index) => {
                // Actualizar los atributos name para todos los campos de entrada en la fila
                tr.querySelectorAll('[name^="detalles["]').forEach(input => {
                    const currentName = input.getAttribute('name');
                    // La expresión regular asegura que solo se reemplace el número entre corchetes
                    const newName = currentName.replace(/detalles\[\d+\]/, `detalles[${index}]`);
                    input.setAttribute('name', newName);
                });
            });
            // Actualizar el índice global para la próxima fila que se cree
            rowIndex = tbody.children.length; 
        }

        form.addEventListener('submit', function(e) {
            const totalDebe = parseFloat(totalDebeEl.textContent);
            const totalHaber = parseFloat(totalHaberEl.textContent);
            
            if (totalDebe !== totalHaber) {
                e.preventDefault();
                alert('Error: El total del Debe y el Haber no coinciden.');
            } else if (totalDebe === 0 && totalHaber === 0) {
                e.preventDefault();
                alert('Error: El asiento no puede tener un valor de cero.');
            }

            // Validación adicional para la descripción 'Constitucion' en el cliente
            if (!asientoConstitucionExists && descripcionInput.value.toLowerCase() !== 'constitucion') {
                e.preventDefault();
                alert('Error: Debe registrar el asiento de "Constitución" primero.');
                descripcionInput.value = 'Constitucion';
            } else if (asientoConstitucionExists && descripcionInput.value.toLowerCase() === 'constitucion') {
                e.preventDefault();
                alert('Error: Ya existe un asiento con la descripción "Constitución". No se puede crear otro.');
            }
        });

        // Initialize with two rows
        createRow();
        createRow();
    });
</script>