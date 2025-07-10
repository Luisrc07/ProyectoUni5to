<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Staff; // Importa los modelos para las reglas de validación
use App\Models\Equipo;

class StoreProyectoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Define tu lógica de autorización aquí.
        // Por ejemplo, solo los usuarios autenticados o con ciertos roles pueden crear proyectos.
        return true; // Por ahora, permitimos a cualquiera para el ejemplo.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion_estimada_minutos' => 'required|integer|min:1',
            'presupuesto' => 'nullable|numeric|min:0',
            'estado' => 'required|string|in:En espera,En proceso,Realizado,Finalizado', // Añadido 'Finalizado' como posible estado
            'lugar' => 'nullable|string|max:255',
            'responsable_id' => 'nullable|exists:staff,id',

            // Validaciones para recursos de personal
            'recursos_personal' => 'nullable|array',
            'recursos_personal.*.staff_id' => [
                'required', // Staff ID es requerido para cada elemento de personal
                'exists:staff,id',
                'distinct', // Asegura que no haya personal duplicado en la misma solicitud
            ],

            // Validaciones para recursos de equipos
            'recursos_equipos' => 'nullable|array',
            'recursos_equipos.*.equipo_id' => [
                'required', // Equipo ID es requerido para cada elemento de equipo
                'exists:equipos,id',
                'distinct', // Asegura que no haya equipos duplicados en la misma solicitud
            ],
            'recursos_equipos.*.cantidad' => [
                'required_with:recursos_equipos.*.equipo_id', // Cantidad es requerida si hay un equipo_id
                'integer',
                'min:1',
                // Custom rule para validar el stock disponible del equipo
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1]; // Obtiene el índice del array (e.g., 0, 1, 2)
                    $equipoId = $this->input("recursos_equipos.{$index}.equipo_id");

                    if ($equipoId) {
                        $equipo = Equipo::find($equipoId);
                        if ($equipo && $value > $equipo->cantidad) {
                            $fail("La cantidad solicitada de '{$equipo->nombre}' ({$value}) excede el stock disponible ({$equipo->cantidad}).");
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
            'recursos_personal.*.staff_id.required' => 'Debe seleccionar un miembro del personal para cada asignación.',
            'recursos_personal.*.staff_id.exists' => 'El personal seleccionado no es válido.',
            'recursos_personal.*.staff_id.distinct' => 'Hay personal duplicado asignado en la misma solicitud.',

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