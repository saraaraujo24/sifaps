async function enviarCadastro(event) {
    event.preventDefault();

    const form = document.getElementById('formCadas-cadastro');
    const formData = new FormData(form);

    // Exibir os dados para depuração
    formData.forEach((value, key) => {
        console.log(key, value);
    });

    try {
        const response = await fetch(form.getAttribute('data-endpoint'), {
            method: 'POST',
            body: formData // Enviar diretamente o FormData sem transformar em JSON
        });

        const contentType = response.headers.get("content-type");

        if (response.ok && contentType && contentType.includes("application/json")) {
            const responseJson = await response.json();
            console.log('Resposta JSON:', responseJson);

            if (responseJson.status === "success") {
                const tipoUsuario = formData.get('tipo_usuario'); // Obtém o tipo de usuário

                if (tipoUsuario === 'profissional') {
                    alert('Cadastro realizado com sucesso! Aguardando aprovação.');
                } else {
                    alert('Cadastro realizado com sucesso!');
                }

               // Redirecionar para a página de login
               window.location.href = '../Login/index.html';
           
            } else {
                alert('Erro: ' + responseJson.message);
            }
        } else {
            const responseText = await response.text();
            console.error('Resposta do backend não é JSON:', responseText);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao enviar os dados.');
    }
}

$(document).ready(function() {
    // Máscaras de entrada
    $('#telefone').inputmask('(99) 99999-9999');
    $('#cpf').inputmask('999.999.999-99');

    // Inicialmente, esconder o formulário
    $('.formCadas-container').hide();
    $('.formCadas-containerbtn').hide();
     // Mostrar formulário ao escolher tipo de usuário
     $('input[name="tipo_usuario"]').change(function() {
        $('.formCadas-container').show(); // Mostrar o formulário
        $('.formCadas-containerbtn').show(); 
        if ($(this).val() === 'usuario') {
            $('#campo-crp').hide();
            $('#campo-profissao').hide();
            $('#nomeusuario').show().attr('required', true); // Torna o campo obrigatório
        } else {
            $('#campo-crp').show();
            $('#campo-profissao').show();
            $('#nomeusuario').hide().removeAttr('required'); // Remove a obrigatoriedade
        }
    });
});