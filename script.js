        let indiceAtual = 0;
        let pontuacao = 0;

        const telaInicial = document.querySelector(".tela-inicial");
        const telaPergunta = document.querySelector(".tela-pergunta");
        const telaFinal = document.querySelector(".tela-final");

        const btnIniciar = document.querySelector(".btn-iniciar");
        const btnEnviar = document.querySelector(".btn-enviar");
        const btnProximo = document.querySelector(".btn-proximo");
        const btnReiniciar = document.querySelector(".btn-reiniciar");

        const textoPergunta = document.getElementById("texto-pergunta");
        const listaOpcoes = document.getElementById("lista-opcoes");
        const feedback = document.getElementById("feedback");
        const resultado = document.querySelector(".resultado");

        // Iniciar quiz
        btnIniciar.addEventListener("click", () => {
            telaInicial.style.display = "none";
            telaPergunta.style.display = "block";
            carregarPergunta();
        });

        // Carregar pergunta
        function carregarPergunta() {
            feedback.textContent = "";
            btnProximo.style.display = "none";
            btnEnviar.style.display = "inline-block";

            let perguntaAtual = perguntas[indiceAtual];
            textoPergunta.textContent = perguntaAtual.pergunta;
            listaOpcoes.innerHTML = "";

            perguntaAtual.opcoes.forEach((opcao, index) => {
                let li = document.createElement("li");
                li.innerHTML = `
                    <label>
                        <input type="radio" name="opcao" value="${index}">
                        ${opcao}
                    </label>
                `;
                listaOpcoes.appendChild(li);
            });
        }

        // Enviar resposta
        btnEnviar.addEventListener("click", () => {
            let respostaSelecionada = document.querySelector("input[name='opcao']:checked");
            if (!respostaSelecionada) {
                alert("Selecione uma opção!");
                return;
            }

            let resposta = parseInt(respostaSelecionada.value);
            if (resposta === perguntas[indiceAtual].resposta) {
                feedback.textContent = "Correto!";
                feedback.className = "correto";
                pontuacao++;
            } else {
                feedback.textContent = "Incorreto!";
                feedback.className = "incorreto";
            }

            btnEnviar.style.display = "none";
            btnProximo.style.display = "inline-block";
        });

        // Próxima pergunta
        btnProximo.addEventListener("click", () => {
            indiceAtual++;
            if (indiceAtual < perguntas.length) {
                carregarPergunta();
            } else {
                mostrarResultado();
            }
        });

        // Mostrar resultado final
        function mostrarResultado() {
            telaPergunta.style.display = "none";
            telaFinal.style.display = "block";
            resultado.textContent = `Você acertou ${pontuacao} de ${perguntas.length} perguntas.`;
        }

        // Reiniciar quiz
        btnReiniciar.addEventListener("click", () => {
            indiceAtual = 0;
            pontuacao = 0;
            telaFinal.style.display = "none";
            telaInicial.style.display = "block";
        });