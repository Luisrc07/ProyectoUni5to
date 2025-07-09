<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Staff; // Importa los modelos para las reglas de validación
use App\Models\Equipo;
use App\Models\ProyectoRecurso; // Necesario para la validación de stock en actualizaciones

class UpdateProyectoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Define tu lógica de autorización aquí.
        // Por ejemplo, solo los usuarios autenticados o con ciertos roles pueden actualizar proyectos.
        // También puedes verificar si el usuario es el responsable del proyecto, etc.
        return true; // Por ahora, permitimos a cualquiera para el ejemplo.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Obtener el proyecto que se está actualizando
        // La ruta asume que el parámetro de ruta se llama 'proyecto'
        $proyecto = $this->route('proyecto');

        $rules = [
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion_estimada_minutos' => 'sometimes|integer|min:1',
            'presupuesto' => 'nullable|numeric|min:0',
            'estado' => 'sometimes|string|in:En espera,En proceso,Realizado,Finalizado', // Añadido 'Finalizado'
            'lugar' => 'nullable|string|max:255',
            'responsable_id' => 'nullable|exists:staff,id',

            // Validaciones para recursos de personal (para actualizar o añadir nuevos)
            'recursos_personal' => 'nullable|array',
            'recursos_personal.*.id' => 'nullable|exists:proyecto_recursos,id', // ID de la asignación existente
            'recursos_personal.*.staff_id' => [
                'required',
                'exists:staff,id',
                'distinct', // Asegura que no haya personal duplicado en la misma solicitud
            ],

            // Validaciones para recursos de equipos (para actualizar o añadir nuevos)
            'recursos_equipos' => 'nullable|array',
            'recursos_equipos.*.id' => 'nullable|exists:proyecto_recursos,id', // ID de la asignación existente
            'recursos_equipos.*.equipo_id' => [
                'required',
                'exists:equipos,id',
                'distinct', // Asegura que no haya equipos duplicados en la misma solicitud
            ],
            'recursos_equipos.*.cantidad' => [
                'required_with:recursos_equipos.*.equipo_id',
                'integer',
                'min:1',
                // Custom rule para validar el stock disponible del equipo en una actualización
                function ($attribute, $value, $fail) use ($proyecto) {
                    $index = explode('.', $attribute)[1]; // Obtiene el índice del array (e.g., 0, 1, 2)
                    $equipoId = $this->input("recursos_equipos.{$index}.equipo_id");
                    $pivotId = $this->input("recursos_equipos.{$index}.id"); // ID de la tabla pivote si existe

                    if ($equipoId) {
                        $equipo = Equipo::find($equipoId);
                        if (!$equipo) {
                            $fail("El equipo seleccionado no es válido.");
                            return;
                        }

                        $currentStock = $equipo->stock;
                        $assignedQuantityOnThisProject = 0;

                        // Si estamos actualizando un registro existente en este proyecto
                        if ($pivotId) {
                            $existingPivot = ProyectoRecurso::find($pivotId);
                            // Asegurarse de que el pivote pertenece al proyecto actual y es del tipo Equipo
                            if ($existingPivot && $existingPivot->proyecto_id == $proyecto->id && $existingPivot->asignable_type == Equipo::class) {
                                // Sumar la cantidad que ya estaba asignada a este proyecto de vuelta al stock "virtual"
                                // para la validación, ya que estamos considerando reasignarla o cambiarla.
                                $assignedQuantityOnThisProject = $existingPivot->cantidad;
                            }
                        }

                        // Calcular el stock disponible para esta operación de validación
                        // Sumamos la cantidad que ya estaba asignada a este proyecto para no penalizarla dos veces.
                        // Luego restamos la cantidad solicitada. Si el resultado es negativo, significa que excede el stock.
                        if (($currentStock + $assignedQuantityOnThisProject) < $value) {
                            $fail("La cantidad solicitada de '{$equipo->nombre}' ({$value}) excede el stock disponible ({$equipo->stock}).");
                        }
                    }
                },
            ],
        ];

        return $rules;
    }

    /**
     * Custom error messages for validation.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'recursos_personal.*.id.exists' => 'La asignación de personal no es válida.',
            'recursos_personal.*.staff_id.required' => 'Debe seleccionar un miembro del personal para cada asignación.',
            'recursos_personal.*.staff_id.exists' => 'El personal seleccionado no es válido.',
            'recursos_personal.*.staff_id.distinct' => 'Hay personal duplicado asignado en la misma solicitud.',

            'recursos_equipos.*.id.exists' => 'La asignación de equipo no es válida.',
            'recursos_equipos.*.equipo_id.required' => 'Debe seleccionar un equipo para cada asignación.',
            'recursos_equipos.*.equipo_id.exists' => 'El equipo seleccionado no es válido.',
            'recursos_equipos.*.equipo_id.distinct' => 'Hay equipos duplicados asignados en la misma solicitud.',
            'recursos_equipos.*.cantidad.required_with' => 'La cantidad es requerida para cada equipo asignado.',
            'recursos_equipos.*.cantidad.min' => 'La cantidad de equipo debe ser al menos :min.',
            'recursos_equipos.*.cantidad.integer' => 'La cantidad de equipo debe ser un número entero.',
            // El mensaje para la validación de stock se maneja directamente en la regla personalizada.
        ];
    }
}
