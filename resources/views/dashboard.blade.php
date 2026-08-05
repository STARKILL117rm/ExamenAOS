<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SOA - Control de Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <nav class="bg-slate-900 p-4 text-white shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold tracking-wide">📦 Dashboard SOA - Monitoreo de Inventario</h1>
            <div class="flex items-center space-x-3">
                <a href="http://localhost:3000/reporte/exportar-csv" 
                   target="_blank"
                   class="bg-indigo-600 hover:bg-indigo-700 text-xs text-white px-3 py-2 rounded-lg font-semibold transition flex items-center gap-1 shadow">
                   📥 Exportar CSV
                </a>
                <span class="bg-green-500 text-xs text-white px-3 py-1 rounded-full font-semibold">Microservicios Activos</span>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-8 p-4">
        <!-- Tarjetas de Métricas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-indigo-500">
                <p class="text-gray-500 text-sm font-bold uppercase">Categorías (PostgreSQL)</p>
                <p id="total-sql" class="text-3xl font-extrabold text-gray-800 mt-2">...</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-emerald-500">
                <p class="text-gray-500 text-sm font-bold uppercase">Productos (MongoDB)</p>
                <p id="total-nosql" class="text-3xl font-extrabold text-gray-800 mt-2">...</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-amber-500">
                <p class="text-gray-500 text-sm font-bold uppercase">Valoración Total</p>
                <p id="valor-total" class="text-3xl font-extrabold text-gray-800 mt-2">...</p>
            </div>
        </div>

        <!-- Gráficas y Alertas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Gráfica Financiera -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-lg font-bold text-gray-700 mb-4">Valuación por Categoría (Node.js)</h2>
                <canvas id="chartFinanciero"></canvas>
            </div>

            <!-- Tabla de Alertas -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-lg font-bold text-red-600 mb-4">⚠️ Alertas de Bajo Stock (< 10)</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="w-full bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wider">
                                <th class="py-3 px-4">Categoría</th>
                                <th class="py-3 px-4">Producto</th>
                                <th class="py-3 px-4 text-center">Stock</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-alertas" class="divide-y divide-gray-200 text-sm text-gray-700">
                            <!-- Datos inyectados vía JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const NODE_API = 'http://localhost:3000';

        async function cargarDashboard() {
            try {
                // 1. Métricas Generales
                const resGeneral = await fetch(`${NODE_API}/reporte-general`);
                const dataGeneral = await resGeneral.json();
                document.getElementById('total-sql').innerText = dataGeneral.metricas.total_categorias_sql;
                document.getElementById('total-nosql').innerText = dataGeneral.metricas.total_productos_nosql;

                // 2. Reporte Financiero
                const resFinanciero = await fetch(`${NODE_API}/reporte/valor-inventario`);
                const dataFinanciero = await resFinanciero.json();
                document.getElementById('valor-total').innerText = dataFinanciero.valor_total_global;

                // Renderizar Gráfica
                const labels = dataFinanciero.desglose_por_categoria.map(item => item.categoria);
                const valores = dataFinanciero.desglose_por_categoria.map(item => parseFloat(item.valor_total_categoria.replace('$', '')));

                new Chart(document.getElementById('chartFinanciero'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Valor ($)',
                            data: valores,
                            backgroundColor: '#6366f1'
                        }]
                    },
                    options: { responsive: true }
                });

                // 3. Alertas de Stock
                const resAlertas = await fetch(`${NODE_API}/reporte/bajo-stock`);
                const dataAlertas = await resAlertas.json();
                const tabla = document.getElementById('tabla-alertas');
                tabla.innerHTML = '';

                dataAlertas.productos.forEach(p => {
                    tabla.innerHTML += `
                        <tr>
                            <td class="py-3 px-4">${p.categoria}</td>
                            <td class="py-3 px-4 font-semibold">${p.producto}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded font-bold">${p.stock_actual}</span>
                            </td>
                        </tr>
                    `;
                });

            } catch (error) {
                console.error("Error al obtener métricas de Node.js:", error);
            }
        }

        cargarDashboard();
    </script>
</body>
</html>