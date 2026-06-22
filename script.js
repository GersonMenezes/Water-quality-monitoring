const ctx = document.getElementById('waterChart').getContext('2d');
let waterChart; // Variável global para guardar o gráfico

async function carregarDados() {
    try {
        const response = await fetch('http://localhost/api/readings');
        const readings = await response.json();
        
        const sensorId = document.getElementById('sensorSelect').value;
        const sensorData = readings.filter(r => r.user_id == sensorId);

        // CORREÇÃO 1: Pega as 15 MAIS NOVAS (0, 15) e inverte para ficar cronológico
        const ultimasLeituras = sensorData.slice(0, 15).reverse();

        const labels = ultimasLeituras.map(r => {
            let data = new Date(r.created_at);
            return data.toLocaleTimeString('pt-BR');
        });
        const dataPh = ultimasLeituras.map(r => r.ph_level);
        const dataTemp = ultimasLeituras.map(r => r.temperature);

        atualizarGrafico(labels, dataPh, dataTemp);
    } catch (error) {
        console.error("Erro ao buscar dados da API:", error);
    }
}

function atualizarGrafico(labels, dataPh, dataTemp) {
    // CORREÇÃO 2: Se o gráfico já existe, só atualiza os dados! Não destrói a tela.
    if (waterChart) {
        waterChart.data.labels = labels;
        waterChart.data.datasets[0].data = dataPh;
        waterChart.data.datasets[1].data = dataTemp;
        
        // O 'none' desativa a animação de "crescimento" inicial toda vez que atualiza
        waterChart.update('none'); 
    } else {
        // Se for a primeira vez carregando a página, cria o gráfico
        waterChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Nível de pH (0 a 14)',
                        data: dataPh,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        yAxisID: 'yPh',
                        tension: 0.4
                    },
                    {
                        label: 'Temperatura (°C)',
                        data: dataTemp,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        yAxisID: 'yTemp',
                        tension: 0.4
                    }
                ]
            },
            options: {
                animation: false, // Gráficos de telemetria reais rodam melhor sem animações
                scales: {
                    yPh: { type: 'linear', position: 'left', min: 0, max: 14 },
                    yTemp: { type: 'linear', position: 'right', min: 10, max: 45 }
                }
            }
        });
    }
}

document.getElementById('sensorSelect').addEventListener('change', carregarDados);
carregarDados();
setInterval(carregarDados, 5000);