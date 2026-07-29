const express = require('express');
const axios = require('axios');
const app = express();
const PORT = 3000;

// Endpoint de orquestación / reporte
app.get('/reporte-general', async (req, res) => {
    try {
        // Consume el endpoint híbrido de Laravel (PHP)
        const response = await axios.get('http://127.0.0.1:8000/api/inventario-completo');
        const inventario = response.data.data;

        let totalCategorias = inventario.length;
        let totalProductosGlobal = 0;

        inventario.forEach(cat => {
            totalProductosGlobal += cat.total_productos;
        });

        res.json({
            servicio: "Microservicio Auxiliar de Reportes (Node.js)",
            estado: "Operativo",
            metricas: {
                total_categorias_sql: totalCategorias,
                total_productos_nosql: totalProductosGlobal
            },
            detalle_inventario: inventario
        });
    } catch (error) {
        res.status(500).json({
            error: "Error al comunicarse con la API de Laravel",
            detalle: error.message
        });
    }
});

app.listen(PORT, () => {
    console.log(`Microservicio de Reportes corriendo en http://localhost:${PORT}`);
});