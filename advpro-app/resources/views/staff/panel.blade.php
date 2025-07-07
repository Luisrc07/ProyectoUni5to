<x-app-layout>
    <h6 class="font-bold text-xl mb-4">Empleados uwu </h6>
    <div class="mb-4">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Crear
        </button>
    </div>

    <table class="table-fixed bg-white border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                <th class="text-center py-2 text-left">Id</th>
                <th class="text-center py-2 text-left">Nombre</th>
                <th class="text-center py-2 text-left">Apellido</th>
                <th class="text-center py-2 text-left">Documento</th>
                <th class="text-center py-2 text-left">Email</th>
                <th class="text-center py-2 text-left">Teléfono</th>
                <th class="text-center py-2 text-left">Dirección</th>
                <th class="text-center py-2 text-left">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staff as $employee)
                <tr>
                    <td class="px-4 py-2">{{ $employee->id }}</td>
                    <td class="px-4 py-2">{{ $employee->nombre }}</td>
                    <td class="px-4 py-2">{{ $employee->apellido }}</td>
                    <td class="px-4 py-2">{{ $employee->tipo_documento }}-{{ $employee->documento }}</td>
                    <td class="px-4 py-2">{{ $employee->email }}</td>
                    <td class="px-4 py-2">{{ $employee->telefono }}</td>
                    <td class="px-4 py-2">{{ $employee->direccion }}</td>
                    <td class="mt-4 px-2 py-1">
                        <form action="{{ $employee->id }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white font-bold py-2 px-3 rounded hover:bg-red-700"><i class="fa-solid fa-trash"></i></button>
                        </form>
                        <a href="{{ $employee->id }}/edit" class="bg-blue-500 text-white font-bold py-2 px-3 rounded hover:bg-blue-700"><i class="fa-solid fa-pencil"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $staff->links() }}
    </div>

    <!-- Modal para Crear Empleado -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Empleado</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="save" method="POST" class="max-w-md mx-auto">
                        @csrf
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="nombre" class="block py-2.5 px-0 w-full border-b-2 border-gray-300" placeholder="Nombre">
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="apellido" class="block py-2.5 px-0 w-full border-b-2 border-gray-300" placeholder="Apellido">
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <select name="tipo_documento" class="block w-full py-2.5 border-b-2 border-gray-300" required>
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="J">J</option>
                                <option value="V">V</option>
                                <option value="E">E</option>
                            </select>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="documento" class="block py-2.5 px-0 w-full border-b-2 border-gray-300" placeholder="Documento">
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="email" name="email" class="block py-2.5 px-0 w-full border-b-2 border-gray-300" placeholder="Email">
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="telefono" class="block py-2.5 px-0 w-full border-b-2 border-gray-300" placeholder="Teléfono">
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="direccion" class="block py-2.5 px-0 w-full border-b-2 border-gray-300" placeholder="Dirección">
                        </div>
                        <center>
                            <button type="submit" class="bg-green-700 text-white px-5 py-2.5 rounded-lg"><i class="fa-solid fa-floppy-disk"></i>  Registrar</button>
                        </center>
                    </form>
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>