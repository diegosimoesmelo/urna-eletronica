(() => {
    const urna = document.querySelector('.urna');
    if (!urna) return;

    const numeroInput = document.querySelector('#numero-voto');
    const tipoInput = document.querySelector('#tipo-voto');
    const botaoConfirma = document.querySelector('#botao-confirma');
    const estadoInicial = document.querySelector('#estado-inicial');
    const estadoCandidato = document.querySelector('#estado-candidato');
    const estadoNulo = document.querySelector('#estado-nulo');
    const estadoBranco = document.querySelector('#estado-branco');
    const instrucao = document.querySelector('#instrucao-confirmar');
    let numero = '';
    let consultaAtual = 0;

    function ocultarEstados() {
        [estadoInicial, estadoCandidato, estadoNulo, estadoBranco, instrucao]
            .forEach(elemento => elemento.classList.add('oculto'));
    }

    function corrigir() {
        numero = '';
        numeroInput.value = '';
        tipoInput.value = 'numero';
        document.querySelector('#digito-1').textContent = '';
        document.querySelector('#digito-2').textContent = '';
        ocultarEstados();
        estadoInicial.classList.remove('oculto');
        botaoConfirma.disabled = true;
        consultaAtual++;
    }

    async function consultarCandidato() {
        const idConsulta = ++consultaAtual;
        ocultarEstados();
        estadoCandidato.classList.remove('oculto');
        instrucao.classList.remove('oculto');
        document.querySelector('#candidato-nome').textContent = 'Consultando...';
        document.querySelector('#candidato-partido').textContent = '—';
        const foto = document.querySelector('#foto-candidato');
        foto.textContent = 'SEM FOTO';
        foto.style.backgroundImage = '';

        try {
            const resposta = await fetch(`${urna.dataset.consultaUrl}/${numero}`, {
                headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
            });
            if (!resposta.ok) throw new Error('Falha na consulta');
            const dados = await resposta.json();
            if (idConsulta !== consultaAtual) return;

            if (!dados.encontrado) {
                estadoCandidato.classList.add('oculto');
                estadoNulo.classList.remove('oculto');
            } else {
                document.querySelector('#candidato-nome').textContent = dados.nome;
                document.querySelector('#candidato-partido').textContent = dados.partido;
                if (dados.foto) {
                    foto.textContent = '';
                    foto.style.backgroundImage = `url("${dados.foto}")`;
                }
            }
            botaoConfirma.disabled = false;
        } catch (_) {
            corrigir();
            alert('Não foi possível consultar o candidato. Tente novamente.');
        }
    }

    document.querySelectorAll('.tecla-numero').forEach(botao => {
        botao.addEventListener('click', () => {
            if (tipoInput.value === 'branco' || numero.length >= 2) return;
            numero += botao.dataset.numero;
            numeroInput.value = numero;
            document.querySelector(`#digito-${numero.length}`).textContent = botao.dataset.numero;
            ocultarEstados();
            estadoCandidato.classList.remove('oculto');
            if (numero.length === 2) consultarCandidato();
        });
    });

    document.querySelector('#botao-corrige').addEventListener('click', corrigir);
    document.querySelector('#botao-branco').addEventListener('click', () => {
        corrigir();
        tipoInput.value = 'branco';
        ocultarEstados();
        estadoBranco.classList.remove('oculto');
        instrucao.classList.remove('oculto');
        botaoConfirma.disabled = false;
    });
    botaoConfirma.addEventListener('click', () => {
        if (!botaoConfirma.disabled) {
            botaoConfirma.disabled = true;
            document.querySelector('#formulario-voto').submit();
        }
    });

    document.addEventListener('keydown', evento => {
        if (/^[0-9]$/.test(evento.key)) {
            document.querySelector(`[data-numero="${evento.key}"]`)?.click();
        } else if (evento.key === 'Backspace' || evento.key === 'Delete') {
            document.querySelector('#botao-corrige').click();
        } else if (evento.key === 'Enter') {
            botaoConfirma.click();
        }
    });
})();
