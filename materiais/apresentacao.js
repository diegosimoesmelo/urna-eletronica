(() => {
    const slides = [...document.querySelectorAll('.slide')];
    const anterior = document.querySelector('#anterior');
    const proximo = document.querySelector('#proximo');
    const contador = document.querySelector('#contador');
    const aulaAtual = document.querySelector('#aula-atual');
    const barra = document.querySelector('#barra-progresso');
    let indice = Math.min(Math.max(Number(location.hash.replace('#', '')) - 1 || 0, 0), slides.length - 1);

    function mostrar(novoIndice) {
        indice = Math.min(Math.max(novoIndice, 0), slides.length - 1);
        slides.forEach((slide, i) => slide.classList.toggle('ativo', i === indice));
        contador.textContent = `${indice + 1} / ${slides.length}`;
        aulaAtual.textContent = slides[indice].dataset.aula;
        barra.style.width = `${((indice + 1) / slides.length) * 100}%`;
        anterior.disabled = indice === 0;
        proximo.disabled = indice === slides.length - 1;
        history.replaceState(null, '', `#${indice + 1}`);
        slides[indice].scrollTop = 0;
    }

    anterior.addEventListener('click', () => mostrar(indice - 1));
    proximo.addEventListener('click', () => mostrar(indice + 1));

    document.addEventListener('keydown', evento => {
        if (['ArrowRight', 'PageDown', ' ', 'Enter'].includes(evento.key)) {
            evento.preventDefault();
            mostrar(indice + 1);
        } else if (['ArrowLeft', 'PageUp', 'Backspace'].includes(evento.key)) {
            evento.preventDefault();
            mostrar(indice - 1);
        } else if (evento.key.toLowerCase() === 'f') {
            document.fullscreenElement ? document.exitFullscreen() : document.documentElement.requestFullscreen();
        } else if (evento.key === 'Home') {
            mostrar(0);
        } else if (evento.key === 'End') {
            mostrar(slides.length - 1);
        }
    });

    let inicioToque = null;
    document.addEventListener('touchstart', evento => inicioToque = evento.touches[0].clientX, {passive: true});
    document.addEventListener('touchend', evento => {
        if (inicioToque === null) return;
        const distancia = evento.changedTouches[0].clientX - inicioToque;
        if (Math.abs(distancia) > 60) mostrar(indice + (distancia < 0 ? 1 : -1));
        inicioToque = null;
    }, {passive: true});

    mostrar(indice);
})();
