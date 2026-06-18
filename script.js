// Cores que serão intercaladas a cada clique
const cores = [
    '#ffffff',  // Branco
    '#ff6b6b',  // Vermelho claro
    '#4ecdc4',  // Turquesa
    '#ffe66d'   // Amarelo
];

// Índice para acompanhar qual cor está sendo usada
let indiceCor = 0;

// Seleciona o elemento
const helloWorld = document.getElementById('hello-world');

// Adiciona o evento de clique
helloWorld.addEventListener('click', function() {
    // Avança para a próxima cor (volta ao início se chegar no fim)
    indiceCor = (indiceCor + 1) % cores.length;

    // Aplica a nova cor
    this.style.color = cores[indiceCor];

    // Adiciona um efeito visual de "pop"
    this.style.transform = 'scale(1.1)';
    setTimeout(() => {
        this.style.transform = 'scale(1.05)';
    }, 100);
});
