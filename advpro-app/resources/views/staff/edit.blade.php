<x-app-layout>
    <form action="{{ url('staff', ['staff' => $staff->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Editar Empleado</h1>
            <a href="{{ url('staff/panel') }}" class="text-sm text-gray-500 hover:text-gray-700">Volver</a>
        </div>

        <div class="flex gap-x-6 mb-6">
            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Nombre y Apellido</label>
                <input type="text" class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full"
                       name="nombre" value="{{$staff->nombre}}" required="">
            </div>
            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Email</label>
                <input type="email" class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full"
                       name="email" value="{{$staff->email}}" required="">
            </div>
        </div> 

        <div class="flex gap-x-6 mb-6">
            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Tipo Documento</label>
                <select name="tipo_documento" class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full" required>
                    <option value="{{$staff->tipo_documento}}" >{{$staff->tipo_documento}}</option>
                    <option value="J">J</option>
                    <option value="V">V</option>
                    <option value="E">E</option>
                </select>
            </div>
            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Documento</label>
                <input type="text" class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full"
                       name="documento" value="{{$staff->documento}}" required="">
            </div>
        </div>

        <div class="flex gap-x-6 mb-6">
            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Teléfono</label>
                <input type="text" class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full"
                       name="telefono" value="{{$staff->telefono}}" required="">
            </div>
            <div class="w-full relative">
                <label class="flex items-center mb-2 text-gray-600 text-sm font-medium">Dirección</label>
                <input type="text" class="block w-full h-11 px-5 py-2.5 border border-gray-300 rounded-full"
                       name="direccion" value="{{$staff->direccion}}" required="">
            </div>
        </div>

        <center>
            <button class="w-52 h-12 shadow-sm rounded-full bg-indigo-600 hover:bg-indigo-800 transition-all duration-700 text-white text-base font-semibold leading-7">
                Guardar
            </button>
        </center>
    </form>
</x-app-layout>