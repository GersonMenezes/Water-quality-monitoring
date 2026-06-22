// const axios = require('axios'); // Se der erro de falta de biblioteca, use o fetch nativo abaixo

// Lista com os 10 sensores reais do banco
const sensors = [
    { id: 2, name: "Ponto Coleta Laranjal", token: "2|w3HJ6f4fSWpDMXE504tbsyAHMezLaso5HqEjAK4Xc327f775" },
    { id: 3, name: "Estação Centro", token: "3|PDA7phzgnPGd5Kuj2xjhR7KG275wlX7erTAxngvt63c04e7c" },
    { id: 4, name: "Filtro Fragata", token: "4|otmHCgt6nMnRiuVdJUMC9sEqKfN9a959SUrK1L6Bfd7942d7" },
    { id: 5, name: "Reservatório Guabiroba", token: "5|XXlckAMgwVYwLhfgdqDjyN9hixNjKNXkplJ2ChFR12519de3" },
    { id: 6, name: "Captação São Gonçalo", token: "6|cEjdTf7KY5d9rhCwBG7zn0vwfYZ3RKdVKsksZ09Oc0b42b08" },
    { id: 7, name: "Estação Três Vendas", token: "7|Z7NhmCgOs9kSQYZxMKabXkRMUhS3gmX7nMF2Y8HOa48ab93f" },
    { id: 8, name: "Ponto Coleta Areal", token: "8|zZBzBqKFyaydFNgSiJGwcDWuXiRQVCVWC16Aovhg6fb980e8" },
    { id: 9, name: "Filtro Porto", token: "9|dJPWilXX6xJPVqqd3z3RA0OGY8JGVYYjdXuZF79Ud61c52b6" },
    { id: 10, name: "Reservatório Py Crespo", token: "10|8ZeUJiCKnZK7wg4tB5REAwSq4qmUJZmjmWsu0dQW34048886" },
    { id: 11, name: "Estação Colônia", token: "11|yFqfs31vqT8DXGrJqHka09dXIPb6S8ylZOntEXS3666417a3" },
];

// Função para gerar variação natural de dados (simulando sensores físicos)
function generateTelemetry(lastPh = 7.0, lastTemp = 20.0) {
    // Adiciona uma variação aleatória simulando ruído do hardware
    const ph = Math.max(0, Math.min(14, lastPh + (Math.random() - 0.5) * 0.4)).toFixed(2);
    const temp = Math.max(10, Math.min(45, lastTemp + (Math.random() - 0.5) * 0.8)).toFixed(2);
    return { ph_level: parseFloat(ph), temperature: parseFloat(temp) };
}

// Loop infinito simulando envio periódico de telemetria
async function startEmulation() {
    console.log("🚀 Emulador de Sensores IoT Augen Iniciado...");
    
    // Armazena o último estado de cada sensor para criar curvas contínuas no gráfico
    const states = sensors.map(s => ({ lastPh: 7.0 + (Math.random() - 0.5), lastTemp: 22.0 + (Math.random() - 0.5) * 5 }));

    setInterval(async () => {
        for (let i = 0; i < sensors.length; i++) {
            const sensor = sensors[i];
            const telemetry = generateTelemetry(states[i].lastPh, states[i].lastTemp);
            
            // Atualiza o estado na memória do script
            states[i].lastPh = telemetry.ph_level;
            states[i].lastTemp = telemetry.temperature;

            try {
                // Envia para o servidor local gerenciado pelo Docker (porta 80)
                const response = await fetch('http://localhost/api/readings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${sensor.token}`
                    },
                    body: JSON.stringify(telemetry)
                });

                if (response.ok) {
                    console.log(`[🟢 ${sensor.name}] Dados enviados -> pH: ${telemetry.ph_level} | Temp: ${telemetry.temperature}°C`);
                } else {
                    console.log(`[🔴 ${sensor.name}] Falha na API: Status ${response.status}`);
                }
            } catch (error) {
                console.error(`[❌ ${sensor.name}] Erro de conexão com o servidor.`);
            }
        }
        console.log("---------------------------------------------------------");
    }, 5000); // Dispara para todas as estações a cada 5 segundos
}

startEmulation();