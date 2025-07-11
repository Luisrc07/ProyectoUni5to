<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff; // Asegúrate de que este sea el nombre correcto de tu modelo de personal
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule; // Importar la clase Rule para validaciones únicas

class PersonalController extends Controller
{
    /**
     * Muestra todos los miembros del personal con filtros y paginación.
     */
    public function index(Request $request)
    {
        $query = Staff::query();

        // --- Aplicar Filtros ---
        // 1. Filtro por Nombre o Documento
        if ($request->filled('nombre_documento')) {
            $searchTerm = $request->input('nombre_documento');
            $query->where(function($q) use ($searchTerm) {
                $q->where('nombre', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('documento', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // 2. Filtro por Cargo
        if ($request->filled('cargo')) {
            $query->where('cargo', $request->input('cargo'));
        }

        // 3. Filtro por Estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // Obtener resultados con paginación
        $staff = $query->paginate(5);

        // Retorna JSON para API o la vista para la web
        return $request->wantsJson()
            ? response()->json($staff, 200)
            : view('personal.panel', compact('staff'));
    }

    /**
     * Genera un reporte PDF del personal filtrado.
     */
    public function exportarPdf(Request $request)
    {
        $query = Staff::query();

        // --- Aplicar la misma lógica de filtros que en el index ---
        if ($request->filled('nombre_documento')) {
            $searchTerm = $request->input('nombre_documento');
            $query->where(function($q) use ($searchTerm) {
                $q->where('nombre', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('documento', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        if ($request->filled('cargo')) {
            $query->where('cargo', $request->input('cargo'));
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // Obtener todos los resultados filtrados, no paginados
        $filteredStaff = $query->get();

        // Generar el PDF usando una vista Blade (por ejemplo, 'personal.reporte_pdf')
        $pdf = Pdf::loadView('personal.reporte_pdf', ['staff' => $filteredStaff]);

        // Retorna el PDF para su descarga
        return $pdf->download('reporte_personal_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Muestra el formulario para crear un nuevo miembro del personal (Web)
     */
    public function create()
    {
        // No se requiere lógica compleja aquí ya que el formulario es un modal en el panel
        return view('personal.create'); // Si tienes una vista específica para crear
    }

    /**
     * Guarda un nuevo miembro del personal en la base de datos (Web y API)
     */
    public function store(Request $request)
    {
        // Reglas de validación con mensajes personalizados
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_documento' => 'required|string|max:50',
            // El documento debe ser único en la tabla 'staff', columna 'documento'
            'documento' => 'required|string|unique:staff,documento|max:20',
            // El email debe ser requerido, un email válido y único en la tabla 'staff', columna 'email'
            'email' => 'required|email|unique:staff,email|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'estado' => 'required|string|in:Activo,Inactivo',
            'cargo' => 'required|string',
        ], [
            'documento.unique' => 'El número de documento ya está registrado para otro personal.',
            'email.unique' => 'El correo electrónico ya está registrado para otro personal.',
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            // Puedes añadir más mensajes personalizados aquí para otras reglas si lo deseas
        ]);

        $staff = Staff::create($validatedData);

        return $request->wantsJson()
            ? response()->json(['message' => 'Personal creado', 'data' => $staff], 201)
            : redirect()->route('personal.index')->with('alert', [
                'type' => 'success',
                'title' => '¡Éxito!',
                'message' => 'Personal creado correctamente',
                'button' => 'Aceptar'
            ]);
    }

    /**
     * Muestra un miembro del personal específico (Web y API)
     */
    public function show(Staff $personal, Request $request)
    {
        return $request->wantsJson()
            ? response()->json($personal, 200)
            : view('personal.show', compact('personal'));
    }

    /**
     * Muestra el formulario para editar un miembro del personal (Web)
     */
    public function edit(Staff $personal)
    {
        return view('personal.edit', compact('personal'));
    }

    /**
     * Actualiza un miembro del personal específico (Web y API)
     */
    public function update(Request $request, Staff $personal)
    {
        // Reglas de validación con mensajes personalizados
        $validatedData = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'tipo_documento' => 'sometimes|required|string|max:50',
            // El documento debe ser único, excepto para el personal actual que se está editando
            'documento' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('staff', 'documento')->ignore($personal->id),
            ],
            // El email debe ser requerido, un email válido y único, excepto para el personal actual
            'email' => [
                'sometimes', // 'sometimes' permite que el campo no sea enviado en la petición si no se modifica
                'required', // 'required' si el campo se envía, debe tener un valor
                'email',
                'max:255',
                Rule::unique('staff', 'email')->ignore($personal->id), // Ignora el ID del personal actual
            ],
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'estado' => 'sometimes|required|string|in:Activo,Inactivo',
            'cargo' => 'sometimes|required|string',
        ], [
            'documento.unique' => 'El número de documento ya está registrado para otro personal.',
            'email.unique' => 'El correo electrónico ya está registrado para otro personal.',
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            // Puedes añadir más mensajes personalizados aquí
        ]);

        $personal->update($validatedData);

        return $request->wantsJson()
            ? response()->json(['message' => 'Personal actualizado', 'data' => $personal], 200)
            : redirect()->route('personal.index')->with('alert', [
                'type' => 'success',
                'title' => '¡Actualizado!',
                'message' => 'Personal actualizado correctamente',
                'button' => 'Aceptar'
            ]);
    }

    /**
     * Cambia el estado de un miembro del personal (Eliminación lógica / Activación/Desactivación) (Web y API)
     */
    public function destroy(Staff $personal, Request $request)
    {
        // Alternar el estado entre Activo y Inactivo
        $newState = ($personal->estado == 'Activo') ? 'Inactivo' : 'Activo';
        $personal->update(['estado' => $newState]);

        $message = ($newState == 'Activo') ? 'Personal activado' : 'Personal desactivado';

        return $request->wantsJson()
            ? response()->json(['message' => $message], 200)
            : redirect()->route('personal.index')->with('alert', [
                'type' => 'success',
                'title' => '¡Estado cambiado!',
                'message' => $message,
                'button' => 'Aceptar'
            ]);
    }
}
