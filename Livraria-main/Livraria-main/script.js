let img = document.getElementById('img-inicial');

let body = document.body;

let container_inicial = document.querySelector('.container-inicial');

let pos_inicio = document.querySelector('.pos-inicio');

img.addEventListener('mouseleave', function () {
    img.style.opacity = '0';
    body.style.transition = "background-color 1s";
    body.style.backgroundColor = "rgb(87, 7, 7)";
    pos_inicio.style.display = 'block';
});

img.addEventListener('transitionend', function (event) {
    if (event.propertyName === 'opacity' && window.getComputedStyle(img).opacity === "0") {
        container_inicial.style.display = 'none';
    }
});

let botoes = [
    { botao: document.querySelector('#d1'), secao: document.querySelector('#div1') },
    { botao: document.querySelector('#d2'), secao: document.querySelector('#div2') },
    { botao: document.querySelector('#d3'), secao: document.querySelector('#div3') },
    { botao: document.querySelector('#d4'), secao: document.querySelector('#div4') },
    { botao: document.querySelector('#d5'), secao: document.querySelector('#div5') },
    { botao: document.querySelector('#d6'), secao: document.querySelector('#div6') },
    { botao: document.querySelector('#d7'), secao: document.querySelector('#div7') },
    { botao: document.querySelector('#d8'), secao: document.querySelector('#div8') }
];

function mostrarSecao(secaoAtiva) {
    botoes.forEach(({secao}) => {
        secao.style.display = secao === secaoAtiva ? 'flex' : 'none';
    });
}

botoes.forEach(({botao, secao}) => {
    botao.addEventListener('click', () => {
        mostrarSecao(secao);
    })
});