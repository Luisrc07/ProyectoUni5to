<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Staff;
use App\Models\Equipo;
use App\Models\ProyectoRecurso; // Necesario para la regla exists

class UpdateProyectoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Define tu lógica de autorización aquí.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion_estimada_minutos' => 'sometimes|integer|min:1',
            'presupuesto' => 'nullable|numeric|min:0',
            'estado' => 'sometimes|string|in:En espera,En proceso,Realizado,Finalizado', // Añadido 'Finalizado'
            'lugar' => 'nullable|string|max:255',
            'responsable_id' => 'nullable|exists:staff,id',
            // 'fecha_inicio_estimada' => 'nullable|date', // Eliminado: Ya no se trabaja con fechas en el proyecto
            // 'fecha_fin_estimada' => 'nullable|date|after_or_equal:fecha_inicio_estimada', // Eliminado: Ya no se trabaja con fechas en el proyecto

            // Validaciones para recursos de personal (para actualizar o añadir nuevos)
            'recursos_personal' => 'nullable|array',
            'recursos_personal.*.id' => 'nullable|exists:proyecto_recursos,id', // ID de la asignación existente
            'recursos_personal.*.staff_id' => [
                'required',
                'exists:staff,id',
            ],

            // Validaciones para recursos de equipos (para actualizar o añadir nuevos)
            'recursos_equipos' => 'nullable|array',
            'recursos_equipos.*.id' => 'nullable|exists:proyecto_recursos,id', // ID de la asignación existente
            'recursos_equipos.*.equipo_id' => [
                'required',
                'exists:equipos,id',
            ],
            'recursos_equipos.*.cantidad' => [
                'required_with:recursos_equipos.*.equipo_id',
                'integer',
                'min:1',
            ],
        ];
    }

    /**
     * Custom error messages for validation.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'recursos_equipos.*.cantidad.required_with' => 'La cantidad es requerida para cada equipo asignado.',
            'recursos_equipos.*.cantidad.min' => 'La cantidad de equipo debe ser al menos :min.',
        ];
    }
}