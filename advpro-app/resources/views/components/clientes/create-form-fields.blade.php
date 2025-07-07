<div class="mt-2 text-sm">
    <span class="text-gray-700 dark:text-gray-400">
        Documento de indentidad
    </span>
    <div>
        <label class="inline-flex items-center text-sm">
            <select name="tipo_documento" id="tipo_documento" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                <option value="" selected disabled>-</option>
                <option value="V">V</option>  {{-- ¡CORREGIDO! --}}
                <option value="J">J</option>  {{-- ¡CORREGIDO! --}}
                <option value="E">E</option>  {{-- ¡CORREGIDO! --}}
                <option value="G">G</option>  {{-- ¡CORREGIDO! --}}
            </select>
        </label>
        <label class="inline-flex items-center text-sm">
            <input name="documento"
                class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                placeholder="Cedula/Rif"
            />
        </label>
    </div>
</div>
{{-- El resto de tu formulario permanece igual --}}
<label class="block mt-2 text-sm">
    <span class="text-gray-700 dark:text-gray-400">Nombre y Apellido</span>
    <input name="nombre"
        class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
        placeholder="Juan Luis Guerra"
    />
</label>
<label class="block mt-2 text-sm">
    <span class="text-gray-700 dark:text-gray-400">Email</span>
    <input type="email" name="email"
        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
        placeholder="Juan Luis Guerra"
    />
</label>
<label class="block mt-2 text-sm">
    <span class="text-gray-700 dark:text-gray-400">Telefono</span>
    <input type="text" name="telefono"
        class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
        placeholder="0424-0426-0414-0416-0412"
    />
</label>
<label class="block mt-2 text-sm">
    <span class="text-gray-700 dark:text-gray-400">Direccion</span>
    <input type="text" name="direccion"
        class="block mt-1 w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
        placeholder="Cualquier calle, ciudad, estado"
    />
</label>