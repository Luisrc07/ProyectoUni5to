<div class="p-6" 
     x-data="cuentaForm({ 
         urlCuentas: '{{ route('contabilidad.cuentas.porTipo', ['tipo' => 'TIPO_PLACEHOLDER']) }}',
         urlCodigo: '{{ route('contabilidad.cuentas.siguienteCodigo') }}'
     })" 
     x-init="init()">
    <h3 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">Crear Nueva Cuenta Contable</h3>

    
    <form action="{{ route('contabilidad.cuentas.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            
            <div>
                <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código (Automático)</label>
                <input type="text" x-model="generatedCode" class="block w-full mt-1 text-sm bg-gray-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 form-input cursor-not-allowed" id="codigo" name="codigo" readonly required>
            </div>

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                <input type="text" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            </div>

            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Cuenta</label>
                <select id="tipo" name="tipo" x-model="selectedType" @change="onTypeChange()" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="activo">Activo</option>
                    <option value="pasivo">Pasivo</option>
                    <option value="patrimonio">Patrimonio</option>
                    <option value="ingreso">Ingreso</option>
                    <option value="egreso">Egreso</option>
                    <option value="costo">Costo</option>
                </select>
            </div>

            <div>
                <label for="cuenta_padre_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cuenta Padre (Opcional)</label>
                <select id="cuenta_padre_id" name="cuenta_padre_id" x-model="selectedParent" @change="onParentChange()" 
                        :disabled="!selectedType || isLoading" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                        :class="{'opacity-50 cursor-not-allowed': !selectedType || isLoading}">
                    <option value="" x-show="!isLoading">Ninguna (Es una cuenta principal)</option>
                    <option value="" x-show="isLoading" disabled>Cargando...</option>
                    <template x-for="cuenta in parentAccounts" :key="cuenta.id_cuenta">
                        <option :value="cuenta.id_cuenta" x-text="`${cuenta.codigo} - ${cuenta.nombre}`"></option>
                    </template>
                </select>
            </div>
            
            <div class="col-span-2 flex items-center mt-2">
                <input type="checkbox" id="es_ajustable" name="es_ajustable" value="1" class="form-checkbox h-5 w-5 text-purple-600 dark:bg-gray-700 dark:border-gray-600 rounded focus:ring-purple-500">
                <label for="es_ajustable" class="ml-2 text-sm text-gray-700 dark:text-gray-300">¿Es ajustable?</label>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Guardar Cuenta
            </button>
        </div>
    </form>
</div>

<script>
    function cuentaForm(config) {
        return {
            urlCuentas: config.urlCuentas,
            urlCodigo: config.urlCodigo,
            
            parentAccounts: [],
            selectedType: '{{ old('tipo') }}' || '',
            selectedParent: '{{ old('cuenta_padre_id') }}' || '',
            generatedCode: '',
            isLoading: false,

            init() {
                if (this.selectedType) {
                    this.fetchParentAccounts();
                }
                this.fetchNextCode();
            },
            
            onTypeChange() {
                this.selectedParent = '';
                this.parentAccounts = [];
                this.fetchParentAccounts();
                this.fetchNextCode();
            },

            onParentChange() {
                this.fetchNextCode();
            },

            async fetchParentAccounts() {
                if (!this.selectedType) return;

                this.isLoading = true;
                const url = this.urlCuentas.replace('TIPO_PLACEHOLDER', this.selectedType);

                const response = await fetch(url);
                this.parentAccounts = await response.json();
                this.isLoading = false;
            },

            async fetchNextCode() {
                if (!this.selectedType) {
                    this.generatedCode = '';
                    return;
                };

                const url = new URL(this.urlCodigo);
                url.searchParams.append('tipo', this.selectedType);
                if (this.selectedParent) {
                    url.searchParams.append('cuenta_padre_id', this.selectedParent);
                }

                const response = await fetch(url.toString());
                const data = await response.json();
                this.generatedCode = data.siguiente_codigo;
            }
        }
    }
</script>