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
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion_estimada_minutos' => 'required|integer|min:1',
            'presupuesto' => 'nullable|numeric|min:0',
            'estado' => 'required|string|in:En espera,En proceso,Realizado,Finalizado', // Añadido 'Finalizado' como posible estado
            'lugar' => 'nullable|string|max:255',
            'responsable_id' => 'nullable|exists:staff,id',
            // 'fecha_inicio_estimada' => 'nullable|date', // Eliminado: Ya no se trabaja con fechas en el proyecto
            // 'fecha_fin_estimada' => 'nullable|date|after_or_equal:fecha_inicio_estimada', // Eliminado: Ya no se trabaja con fechas en el proyecto

            // Validaciones para recursos de personal
            'recursos_personal' => 'nullable|array',
            'recursos_personal.*.staff_id' => [
                'required', // Staff ID es requerido para cada elemento de personal
                'exists:staff,id',
            ],

            // Validaciones para recursos de equipos
            'recursos_equipos' => 'nullable|array',
            'recursos_equipos.*.equipo_id' => [
                'required', // Equipo ID es requerido para cada elemento de equipo
                'exists:equipos,id',
            ],
            'recursos_equipos.*.cantidad' => [
                'required_with:recursos_equipos.*.equipo_id', // Cantidad es requerida si hay un equipo_id
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