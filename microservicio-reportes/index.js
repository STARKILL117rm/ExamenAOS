const express = require('express');
const axios = require('axios');
const app = express();
const PORT = 3000;

const LARAVEL_API = 'http://127.0.0.1:8000/api/inventario-completo';
const API_KEY = 'SecretTokenSOA2026';

// Cliente HTTP configurado
const client = axios.create({
    baseURL: LARAVEL_API,
    headers: {
        'X-API-KEY': API_KEY,
        'Accept': 'application/json'
    }
});

// Sistema de Caché en Memoria (SOA Performance)
let cacheInventario = null;
let ultimaActualizacion = 0;
const TIEMPO_CACHE_MS = 30000; // 30 segundos

async function obtenerInventarioConCache() {
    const ahora = Date.now();
    if (cacheInventario && (ahora - ultimaActualizacion < TIEMPO_CACHE_MS)) {
        return { data: cacheInventario, desdeCache: true };
    }

    const response = await client.get('');
    cacheInventario = response.data.data;
    ultimaActualizacion = ahora;
    return { data: cacheInventario, desdeCache: false };
}

// 1. Reporte General Consolidado con Caché
app.get('/reporte-general', async (req, res) => {
    try {
        const { data: inventario, desdeCache } = await obtenerInventarioConCache();

        let totalCategorias = inventario.length;
        let totalProductosGlobal = 0;

        inventario.forEach(cat => {
            totalProductosGlobal += cat.total_productos;
        });

        res.json({
            servicio: "Microservicio Auxiliar de Reportes (Node.js)",
            autenticacion: "API Key Verificada (Seguridad SOA)",
            performance: desdeCache ? "Respuesta desde Caché (30s TTL)" : "Consulta en vivo a Laravel",
            metricas: {
                total_categorias_sql: totalCategorias,
                total_productos_nosql: totalProductosGlobal
            },
            detalle_inventario: inventario
        });
    } catch (error) {
        res.status(error.response?.status || 500).json({
            error: "Error al comunicarse con la API de Laravel",
            detalle: error.response?.data || error.message
        });
    }
});

// 2. Reporte de Alertas: Bajo Stock
app.get('/reporte/bajo-stock', async (req, res) => {
    try {
        const { data: inventario } = await obtenerInventarioConCache();
        let productosBajoStock = [];

        inventario.forEach(cat => {
            cat.productos.forEach(prod => {
                if (parseInt(prod.stock) < 10) {
                    productosBajoStock.push({
                        categoria: cat.nombre_categoria,
                        producto: prod.nombre,
                        stock_actual: prod.stock,
                        precio: prod.precio
                    });
                }
            });
        });

        res.json({
            alerta: "Productos que requieren reabastecimiento",
            total_alertas: productosBajoStock.length,
            productos: productosBajoStock
        });
    } catch (error) {
        res.status(error.response?.status || 500).json({
            error: "Error al generar reporte de stock",
            detalle: error.response?.data || error.message
        });
    }
});

// 3. Reporte Financiero: Valor Total
app.get('/reporte/valor-inventario', async (req, res) => {
    try {
        const { data: inventario } = await obtenerInventarioConCache();
        let valorTotalEmpresa = 0;

        const desglose = inventario.map(cat => {
            let valorCategoria = 0;
            cat.productos.forEach(prod => {
                valorCategoria += parseFloat(prod.precio) * parseInt(prod.stock);
            });
            valorTotalEmpresa += valorCategoria;

            return {
                categoria: cat.nombre_categoria,
                valor_total_categoria: `$${valorCategoria.toFixed(2)}`
            };
        });

        res.json({
            resumen_financiero: "Valuación global de inventario",
            valor_total_global: `$${valorTotalEmpresa.toFixed(2)}`,
            desglose_por_categoria: desglose
        });
    } catch (error) {
        res.status(error.response?.status || 500).json({
            error: "Error al calcular valor de inventario",
            detalle: error.response?.data || error.message
        });
    }
});

// 4. NUEVO: Exportación Directa a CSV (Descarga de archivo)
app.get('/reporte/exportar-csv', async (req, res) => {
    try {
        const { data: inventario } = await obtenerInventarioConCache();

        let csv = 'Categoria,Producto,Precio,Stock,Subtotal\n';

        inventario.forEach(cat => {
            cat.productos.forEach(prod => {
                const subtotal = (parseFloat(prod.precio) * parseInt(prod.stock)).toFixed(2);
                csv += `"${cat.nombre_categoria}","${prod.nombre}",${prod.precio},${prod.stock},${subtotal}\n`;
            });
        });

        res.setHeader('Content-Type', 'text/csv');
        res.setHeader('Content-Disposition', 'attachment; filename="reporte_inventario_soa.csv"');
        res.status(200).send(csv);
    } catch (error) {
        res.status(500).json({ error: "Error al generar CSV", detalle: error.message });
    }
});

app.listen(PORT, () => {
    console.log(`Microservicio de Reportes corriendo en http://localhost:${PORT}`);
});