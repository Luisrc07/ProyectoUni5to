<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CuentaContable; // Asegúrate de que este modelo exista y esté correctamente referenciado

class CuentaContableSeeder extends Seeder
{
    /**
     * Ejecuta los seeds de la base de datos.
     *
     * @return void
     */
    public function run(): void
    {
        // 1. Deshabilitar la verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Trunca la tabla CuentaContable (ahora permitido)
        // Opcional: Útil durante el desarrollo para asegurar un estado limpio.
        // Ten cuidado en producción si ya hay datos reales que no quieres perder.
        DB::table('cuenta_contable')->truncate();

        // Array para almacenar los IDs de las cuentas insertadas, mapeados por su código.
        $insertedIds = [];

        // Función auxiliar para insertar una cuenta y guardar su ID.
        $insertAccount = function ($codigo, $nombre, $tipo, $es_ajustable = false, $parentCodigo = null) use (&$insertedIds) {
            $parentId = null;
            if ($parentCodigo && isset($insertedIds[$parentCodigo])) {
                $parentId = $insertedIds[$parentCodigo];
            }

            $id = DB::table('cuenta_contable')->insertGetId([
                'codigo' => $codigo,
                'nombre' => $nombre,
                'tipo' => $tipo,
                'es_ajustable' => $es_ajustable,
                'cuenta_padre_id' => $parentId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $insertedIds[$codigo] = $id;
            return $id;
        };

        // --- PLAN DE CUENTAS (El resto de tu código del seeder es el mismo) ---

        // 1. ACTIVO (Cuentas que representan los bienes y derechos de la empresa)
        $insertAccount('1000', 'ACTIVO', 'activo');
            $insertAccount('1100', 'Activo Circulante', 'activo', false, '1000');
                $insertAccount('1101', 'Caja y Bancos', 'activo', false, '1100');
                    $insertAccount('110101', 'Caja', 'activo', true, '1101');
                    $insertAccount('110102', 'Bancos', 'activo', true, '1101');
                $insertAccount('1102', 'Cuentas por Cobrar', 'activo', false, '1100');
                    $insertAccount('110201', 'Clientes', 'activo', true, '1102');
                    $insertAccount('110202', 'Documentos por Cobrar', 'activo', true, '1102');
                $insertAccount('1103', 'Inventarios', 'activo', false, '1100');
                    $insertAccount('110301', 'Materiales de Oficina', 'activo', true, '1103');
                    $insertAccount('110302', 'Suministros de Producción (ej. Cintas, Discos Duros)', 'activo', true, '1103');
                $insertAccount('1104', 'Pagos Anticipados', 'activo', false, '1100');
                    $insertAccount('110401', 'Alquileres Pagados por Anticipado', 'activo', true, '1104');
                    $insertAccount('110402', 'Seguros Pagados por Anticipado', 'activo', true, '1104');
                $insertAccount('1105', 'Impuestos como Activos', 'activo', false, '1100');
                    $insertAccount('11051', 'IVA Credito Fiscal', 'activo', false, '1105');
            $insertAccount('1200', 'Activo No Circulante (Fijo)', 'activo', false, '1000');
                $insertAccount('1201', 'Propiedad, Planta y Equipo', 'activo', false, '1200');
                    $insertAccount('120101', 'Equipo de Grabación (Cámaras, Micrófonos, Luces)', 'activo', true, '1201');
                    $insertAccount('120102', 'Equipo de Edición (Computadoras, Software)', 'activo', true, '1201');
                    $insertAccount('120103', 'Mobiliario y Equipo de Oficina', 'activo', true, '1201');
                    $insertAccount('120104', 'Vehículos', 'activo', true, '1201');
                $insertAccount('1202', 'Depreciación Acumulada', 'activo', false, '1200');
                    $insertAccount('120201', 'Depreciación Acumulada Equipo de Grabación', 'activo', true, '1202');
                    $insertAccount('120202', 'Depreciación Acumulada Equipo de Edición', 'activo', true, '1202');
                    $insertAccount('120203', 'Depreciación Acumulada Mobiliario y Equipo de Oficina', 'activo', true, '1202');
                    $insertAccount('120204', 'Depreciación Acumulada Vehículos', 'activo', true, '1202');

        // 2. PASIVO
        $insertAccount('2000', 'PASIVO', 'pasivo');
            $insertAccount('2100', 'Pasivo Circulante', 'pasivo', false, '2000');
                $insertAccount('2101', 'Cuentas por Pagar', 'pasivo', false, '2100');
                    $insertAccount('210101', 'Proveedores', 'pasivo', true, '2101');
                    $insertAccount('210102', 'Acreedores Varios', 'pasivo', true, '2101');
                $insertAccount('2102', 'Documentos por Pagar', 'pasivo', true, '2100');
                $insertAccount('2103', 'Impuestos por Pagar', 'pasivo', false, '2100');
                    $insertAccount('210301', 'IVA Debito Fiscal', 'pasivo', true, '2103');
                    $insertAccount('210302', 'Impuesto sobre la Renta por Pagar', 'pasivo', true, '2103');
                $insertAccount('2104', 'Ingresos Diferidos', 'pasivo', false, '2100');
                    $insertAccount('210401', 'Anticipos de Clientes', 'pasivo', true, '2104');
            $insertAccount('2200', 'Pasivo a Largo Plazo', 'pasivo', false, '2000');
                $insertAccount('2201', 'Préstamos Bancarios a Largo Plazo', 'pasivo', true, '2200');

        // 3. PATRIMONIO
        $insertAccount('3000', 'PATRIMONIO', 'patrimonio');
            $insertAccount('3100', 'Capital', 'patrimonio', false, '3000');
                $insertAccount('3101', 'Capital Social', 'patrimonio', true, '3100');
                $insertAccount('3102', 'Aportaciones de Socios', 'patrimonio', true, '3100');
            $insertAccount('3200', 'Reservas', 'patrimonio', false, '3000');
                $insertAccount('3201', 'Reserva Legal', 'patrimonio', true, '3200');
            $insertAccount('3300', 'Resultados Acumulados', 'patrimonio', false, '3000');
                $insertAccount('3301', 'Utilidades Retenidas', 'patrimonio', true, '3300');
                $insertAccount('3302', 'Pérdidas Acumuladas', 'patrimonio', true, '3300');
            $insertAccount('3400', 'Resultado del Ejercicio', 'patrimonio', false, '3000');
                $insertAccount('3401', 'Utilidad del Ejercicio', 'patrimonio', true, '3400');
                $insertAccount('3402', 'Pérdida del Ejercicio', 'patrimonio', true, '3400');

        // 4. INGRESO
        $insertAccount('4000', 'INGRESOS', 'ingreso');
            $insertAccount('4100', 'Ingresos Operacionales', 'ingreso', false, '4000');
                $insertAccount('4101', 'Ingresos por Servicios de Grabación', 'ingreso', true, '4100');
                $insertAccount('4102', 'Ingresos por Servicios de Edición y Post-producción', 'ingreso', true, '4100');
                $insertAccount('4103', 'Ingresos por Alquiler de Equipo Audiovisual', 'ingreso', true, '4100');
                $insertAccount('4104', 'Ingresos por Producción Audiovisual Completa', 'ingreso', true, '4100');
            $insertAccount('4200', 'Otros Ingresos', 'ingreso', false, '4000');
                $insertAccount('4201', 'Ingresos Financieros', 'ingreso', true, '4200');

        // 5. EGRESO
        $insertAccount('5000', 'EGRESOS', 'egreso');
            $insertAccount('5100', 'Gastos de Administración', 'egreso', false, '5000');
                $insertAccount('5101', 'Sueldos y Salarios Administrativos', 'egreso', true, '5100');
                $insertAccount('5102', 'Alquiler de Oficinas', 'egreso', true, '5100');
                $insertAccount('5103', 'Servicios Públicos (Oficina)', 'egreso', true, '5100');
                $insertAccount('5104', 'Gastos de Oficina y Papelería', 'egreso', true, '5100');
                $insertAccount('5105', 'Depreciación de Activos (Administrativos)', 'egreso', true, '5100');
                $insertAccount('5106', 'Gastos de Mantenimiento (Oficina y Equipo General)', 'egreso', true, '5100');
                $insertAccount('5107', 'Gastos Legales y Contables', 'egreso', true, '5100');
            $insertAccount('5200', 'Gastos de Venta', 'egreso', false, '5000');
                $insertAccount('5201', 'Gastos de Publicidad y Marketing', 'egreso', true, '5200');
                $insertAccount('5202', 'Comisiones sobre Ventas', 'egreso', true, '5200');
            $insertAccount('5300', 'Gastos Financieros', 'egreso', true, '5000');
                $insertAccount('5301', 'Intereses Pagados', 'egreso', true, '5300');

        // 6. COSTO
        $insertAccount('6000', 'COSTOS', 'costo');
            $insertAccount('6100', 'Costos de Producción', 'costo', false, '6000');
                $insertAccount('6101', 'Sueldos y Salarios de Producción (Técnicos, Editores, Directores)', 'costo', true, '6100');
                $insertAccount('6102', 'Alquiler de Locaciones/Estudios para Grabación', 'costo', true, '6100');
                $insertAccount('6103', 'Suministros y Materiales de Producción (Atrezzo, Vestuario, Consumibles)', 'costo', true, '6100');
                $insertAccount('6104', 'Servicios de Terceros (Actores, Maquilladores, Catering, Diseñadores)', 'costo', true, '6100');
                $insertAccount('6105', 'Transporte y Logística de Producción', 'costo', true, '6100');
                $insertAccount('6106', 'Depreciación de Equipo de Producción', 'costo', true, '6100');
                $insertAccount('6107', 'Mantenimiento de Equipo de Producción', 'costo', true, '6100');

        // 3. Habilitar la verificación de claves foráneas nuevamente
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}