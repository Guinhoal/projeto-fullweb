const fantasma = document.querySelector('.logo-imagem');
const obstaculos = document.querySelector('.obstaculos');
const obstaculoUm = document.querySelector('#livro-fantasma-azul');
const obstaculoDois = document.querySelector('#livro-caveira');
const obstaculoTres = document.querySelector('#livro-fantasma-cinza');
const tela = document.querySelector('.tela');
const telaJogo = document.querySelector('.quadro-jogo');
let pontuacao = 0;
let pontuacaoFinal = 0;

const pulo = () => {
    fantasma.classList.add('pulo');
    setTimeout(() => {
        fantasma.classList.remove('pulo');
    }, 500);
}

document.addEventListener('keydown', () => {
    pulo();
});

const pontua = () => {
    const obstaculoUmPosicao = obstaculoUm.getBoundingClientRect().right;
    const obstaculoDoisPosicao = obstaculoDois.getBoundingClientRect().right;
    const obstaculoTresPosicao = obstaculoTres.getBoundingClientRect().right;

    const esquerda = fantasma.getBoundingClientRect().left;

    if ((obstaculoUmPosicao < esquerda) || obstaculoDoisPosicao < esquerda || obstaculoTresPosicao < esquerda) {
        pontuacao++;
        console.log('pontuacao');
    }
}

const telaFinal = () => {
    tela.style.display = 'block';
    fantasma.classList.add('pulo');
    mostraCreditos();

    document.getElementById('botaoVoltar').addEventListener('click', function (event) {
        tela.style.display = 'none';
    });

    // Enviar pontuação final ao servidor
    fetch('atualiza_creditos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ creditos: pontuacaoFinal })
    })
        .then(response => response.json())
        .then(data => {
            console.log('Créditos atualizados:', data);
        })
        .catch(error => {
            console.error('Erro ao atualizar créditos:', error);
        });
}

const mostraCreditos = () => {
    document.getElementById('pontuacao').innerHTML = 'CRÉDITOS: $' + pontuacaoFinal + ',00';
}

const loop = setInterval(() => {
    pontua();

    const obstaculoUmPosicao = obstaculoUm.getBoundingClientRect().left;
    const obstaculoDoisPosicao = obstaculoDois.getBoundingClientRect().left;
    const obstaculoTresPosicao = obstaculoTres.getBoundingClientRect().left;

    const obstaculosPosicao = obstaculos.getBoundingClientRect().left;
    const fantasmaPosicao = +window.getComputedStyle(fantasma).bottom.replace('px', '');

    if ((obstaculoUmPosicao <= 195 && obstaculoUmPosicao + 130 > 200) || (obstaculoDoisPosicao <= 195 && obstaculoDoisPosicao + 130 > 200) || (obstaculoTresPosicao <= 195 && obstaculoTresPosicao + 130 > 200)) {
        if (fantasmaPosicao <= 120) {

            fantasma.style.animation = 'none';
            fantasma.style.left = `${window.getComputedStyle(fantasma).left}`;
            fantasma.src = 'imagens-jogo/morto.png';
            fantasma.style.width = '130px';

            obstaculoUm.style.left = obstaculoUmPosicao + 'px';
            obstaculoDois.style.left = obstaculoUmPosicao + 'px';
            obstaculoTres.style.left = obstaculoUmPosicao + 'px';
            obstaculos.style.animation = 'none';

            setTimeout(() => {
                telaJogo.style.opacity = '0.8';
                tela.opacity = '1';
                telaFinal();
            }, 1000);

            // Adicione este código onde a pontuação final é calculada, antes de `clearInterval(loop)`
            pontuacaoFinal = Math.trunc(pontuacao / 15);

            // Enviar os créditos para o servidor
            fetch('atualizar_creditos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ creditos: pontuacaoFinal })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Créditos atualizados:', data);
                })
                .catch(error => {
                    console.error('Erro ao atualizar créditos:', error);
                });

            // Restante do código...
            clearInterval(loop);

        }
    }
}, 10);