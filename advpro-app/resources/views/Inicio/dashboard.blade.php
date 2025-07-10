<x-layouts.app>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- En el <head> o antes de cerrar </body> -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    
    

    
    <!-- Usa esta sintaxis (sin "path:") -->
    <script src="{{ asset('js/charts-bars.js') }}" defer></script>
    <script src="{{ asset('js/charts-pie.js') }}" defer></script>
    <script src="{{ asset('js/charts-lines.js') }}" defer></script>
    

    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Dashboard
    </h2>
    
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <!-- Card -->
        <div
        class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
        >
        <div
            class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
            ></path>
            </svg>
        </div>
        <div>
            <p
            class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
            >
            Clientes Registrados
            </p>
            <p
            class="text-lg font-semibold text-gray-700 dark:text-gray-200"
            >
            {{ $totalClientes }}
            </p>
        </div>
        </div>
        <!-- Card -->
        <div
        class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
        >
        <div
            class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path
                fill-rule="evenodd"
                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                clip-rule="evenodd"
            ></path>
            </svg>
        </div>
        <div>
            <p
            class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
            >
            Balance de Contratos
            </p>
            <p
            class="text-lg font-semibold text-gray-700 dark:text-gray-200"
            >
            $ {{ number_format($balanceContratos, 2, '.', ',') }}
            </p>
        </div>
        </div>
        <!-- Card -->
        <div
        class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
        >
        <div
            class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"
            ></path>
            </svg>
        </div>
        <div>
            <p
            class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
            >
            Contratos Activos
            </p>
            <p
            class="text-lg font-semibold text-gray-700 dark:text-gray-200"
            >
            {{ $contratosActivos }}
            </p>
        </div>
        </div>
        <!-- Card -->
        <div
        class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
        >
        <div
            class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path
                fill-rule="evenodd"
                d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"
                clip-rule="evenodd"
            ></path>
            </svg>
        </div>
        <div>
            <p
            class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
            >
            Contratos Finalizados
            </p>
            <p
            class="text-lg font-semibold text-gray-700 dark:text-gray-200"
            >
            {{ $contratosFinalizados }}
            </p>
        </div>
        </div>
    </div>

    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Graficos y Estadísticas
    </h2>

    <div class="flex items-end space-x-4 mb-4">
        
        {{-- Filtro por Tipo de Documento --}}
        <div class="flex flex-col">
            <label for="fuente_datos" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Fuente de Datos</label>
            <select name="fuente_datos" id="fuente_datos" class="block w-64 h-10 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner">
                <option value="" selected disabled>Seleccione una fuente</option>
                <optgroup label="Producción">
                    <option value="contratos">Contratos</option>
                    <option value="clientes">Clientes</option>
                </optgroup>
                <optgroup label="Contables">
                    <option value="activo">Activos</option>
                    <option value="pasivo">Pasivos</option>
                    <option value="ingreso">Ingresos</option>
                    <option value="egreso">Egresos</option>
                    <option value="patrimonio">Patrimonio</option>
                </optgroup>
            </select>
        </div>

        <div class="flex flex-col">
            <label for="datos_graficar" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Datos a Graficar</label>
            <select name="datos_graficar" id="datos_graficar" class="block w-64 h-10 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner" disabled>
                <option value="">Seleccione primero una fuente</option>
            </select>
        </div>

        {{-- Filtro por Tipo de Gráfico --}}
        <div class="flex flex-col ">
            <label for="tipo-grafico" class="text-xs font-bold text-gray-400 dark:text-gray-400 mb-1 uppercase">Tipo de Grafico</label>
            <select name="tipo-grafico" id="tipo-grafico"
                class="block w-64 h-10 px-4 py-2 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray rounded-lg border-2 border-gray-700 shadow-inner"
            >
                <option value="" selected disabled>Seleccionar</option>
                <option value="pie">Pastel</option>
                <option value="line">Líneas</option>
                <option value="bar">Barras</option>
            </select>
        </div>

        {{-- 3. Botón para Aplicar Filtros (con las clases del componente) --}}
        <button id="btn-graficar" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-300 dark:bg-purple-500 dark:hover:bg-purple-600 dark:focus:ring-purple-800">
            Generar Gráfico
        </button>
        <button id="btn-exportar-pdf" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 ml-2">
            <i class="fas fa-file-pdf mr-2"></i>Exportar a PDF
        </button>
    </div>

    <script>
            $(document).ready(function() {
                $('#fuente_datos').change(function() {
                    const valorSeleccionado = $(this).val();
                    const $selectDatos = $('#datos_graficar');
                    
                    // Resetear el select
                    $selectDatos.prop('disabled', false).html('<option value="">Cargando...</option>');
                    
                    // Definir opciones de contabilidad
                    const opcionesContabilidad = ['activo', 'pasivo', 'ingreso', 'egreso','patrimonio'];
                    
                    if (!valorSeleccionado) {
                        $selectDatos.prop('disabled', true).html('<option value="">Seleccione primero una fuente</option>');
                        return;
                    }
                    
                    // Si no es contabilidad, mostrar solo "Todos"
                    if (!opcionesContabilidad.includes(valorSeleccionado)) {
                        $selectDatos.html('<option value="todos">Todos</option>');
                        return;
                    }
                    
                    // Hacer petición AJAX solo para contabilidad
                    $.ajax({
                        url: "{{ route('dashboard.obtener-cuentas') }}",
                        type: "GET",
                        data: { tipo: valorSeleccionado },
                        success: function(response) {
                            let options = '<option value=""  select>Seleccione una cuenta</option>';
                            
                            
                            response.forEach(cuenta => {
                                options += `<option value="${cuenta.id_cuenta}">${cuenta.codigo} - ${cuenta.nombre}</option>`;
                            });
                            
                            $selectDatos.html(options);
                        },
                        error: function() {
                            $selectDatos.html('<option value="">Error al cargar datos</option>');
                        }
                    });
                });
            });

            let chartInstance = null;

            function generarGrafico() {
                const cuentaId = $('#datos_graficar').val();
                const tipoGrafico = $('#tipo-grafico').val();
                
                if (!cuentaId) {
                    alert('Por favor seleccione una cuenta');
                    return;
                }

                // Mostrar loading usando tu estructura
                $('#grafico-container').html(`
                    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                        <div class="flex justify-center py-10">
                            <i class="fas fa-spinner fa-spin fa-2x text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                `);

                $.ajax({
                    url: "{{ route('dashboard.obtener-datos-grafico') }}",
                    type: "POST",
                    data: {
                        cuenta_id: cuentaId,
                        tipo_grafico: tipoGrafico,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        renderizarGrafico(response);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $('#grafico-container').html(`
                            <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                                <div class="text-red-500 dark:text-red-400 text-center py-4">
                                    Error al generar el gráfico
                                </div>
                            </div>
                        `);
                    }
                });
            }

            function renderizarGrafico(data) {
                // Destruir gráfico anterior si existe
                if (chartInstance) {
                    chartInstance.destroy();
                }

                // Crear contenedor con tu estructura
                const html = `
                    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                        <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                            ${data.titulo}
                        </h4>
                        <canvas id="dynamic-chart"></canvas>
                        <div class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400" id="chart-legend">
                            <!-- La leyenda se generará dinámicamente -->
                        </div>
                    </div>
                `;
                
                $('#grafico-container').html(html);

                // Configuración común para todos los gráficos
                const commonOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false, // Usaremos nuestra propia leyenda
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw.toLocaleString()}`;
                                }
                            }
                        }
                    }
                };

                // Configurar colores según tu esquema
                const colors = {
                    debe: {
                        bg: 'rgba(79, 70, 229, 0.7)', // purple-600
                        border: 'rgba(79, 70, 229, 1)'
                    },
                    haber: {
                        bg: 'rgba(20, 184, 166, 0.7)', // teal-500
                        border: 'rgba(20, 184, 166, 1)'
                    }
                };

                // Preparar datasets según tipo de gráfico
                let datasets = [];
                if (data.tipo_grafico === 'pie' || data.tipo_grafico === 'doughnut') {
                    datasets = [
                        {
                            data: [data.total_debe, data.total_haber],
                            backgroundColor: [colors.debe.bg, colors.haber.bg],
                            borderColor: [colors.debe.border, colors.haber.border],
                            borderWidth: 1
                        }
                    ];
                } else {
                    datasets = [
                        {
                            label: 'Debe',
                            data: data.debeData,
                            backgroundColor: colors.debe.bg,
                            borderColor: colors.debe.border,
                            borderWidth: 1
                        },
                        {
                            label: 'Haber',
                            data: data.haberData,
                            backgroundColor: colors.haber.bg,
                            borderColor: colors.haber.border,
                            borderWidth: 1
                        }
                    ];
                }

                // Crear el gráfico
                const ctx = document.getElementById('dynamic-chart').getContext('2d');
                chartInstance = new Chart(ctx, {
                    type: data.tipo_grafico,
                    data: {
                        labels: data.labels,
                        datasets: datasets
                    },
                    options: commonOptions
                });

                // Generar leyenda personalizada
                const legendHtml = `
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 mr-1 rounded-full" style="background-color: ${colors.debe.bg}"></span>
                        <span>Debe</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 mr-1 rounded-full" style="background-color: ${colors.haber.bg}"></span>
                        <span>Haber</span>
                    </div>
                `;
                $('#chart-legend').html(legendHtml);
            }

            // Asignar evento al botón
           $('#btn-graficar').click(function() {
                generarGrafico();
            });

            document.getElementById('btn-exportar-pdf').addEventListener('click', async function() {
    // Configuración inicial
    const element = document.getElementById('grafico-container');
    const { jsPDF } = window.jspdf;
    
    // Capturar el contenedor
    const canvas = await html2canvas(element, {
        scale: 2,
        logging: false,
        useCORS: true,
        scrollY: -window.scrollY
    });

    // Crear PDF horizontal
    const pdf = new jsPDF({
        orientation: 'landscape',
        unit: 'mm'
    });

    // Dimensiones
    const pageWidth = 277;  // Ancho útil (297 - 20mm márgenes)
    const pageHeight = 160; // Altura para gráfico
    
    // Calcular dimensiones manteniendo proporción
    const imgRatio = canvas.width / canvas.height;
    let imgWidth = pageWidth;
    let imgHeight = imgWidth / imgRatio;
    
    if (imgHeight > pageHeight) {
        imgHeight = pageHeight;
        imgWidth = imgHeight * imgRatio;
    }

    // Posiciones (solo gráfico centrado)
    const xPos = (297 - imgWidth) / 2; // Centrado horizontal
    const yPos = 25; // Posición original para gráfico

    // -- Títulos y pie (posición original) --
    pdf.setFontSize(18);
    pdf.setTextColor(30, 30, 30);
    pdf.text('Reporte Grafico Gerencial', 20, 15); // Izquierda
    
    pdf.setFontSize(12);
    pdf.text(`Datos: ${document.querySelector('#grafico-container h4').innerText}`, 20, 20);
    pdf.text(`Generado: ${new Date().toLocaleDateString()}`, 250, 20, { align: 'right' });
    
    // Gráfico (centrado)
    pdf.addImage(
        canvas.toDataURL('image/png'), 
        'PNG', 
        xPos, // Centrado horizontal
        yPos, // Posición vertical original
        imgWidth, 
        imgHeight
    );

    // Pie de página (original)
    pdf.setFontSize(10);
    pdf.setTextColor(100);
    pdf.text('© AudioVisualPro', 20, 190);

    // Descargar
    pdf.save(`reporte_contable_${new Date().toLocaleDateString()}.pdf`);
});
    </script>

    <div id="grafico-container" class="mt-6">
        <!-- El gráfico se insertará aquí manteniendo tus estilos -->
    </div>



    
</x-layouts.app>   
