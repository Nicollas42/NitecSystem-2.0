<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

// Componentes do PrimeVue
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Button from 'primevue/button';
import Message from 'primevue/message';

const router = useRouter();

// Estado do formulário
const formulario = ref({
    nome_completo: '',
    email: '',
    celular: '',
    password: '',
    password_confirmation: ''
});

const carregando = ref(false);
const erro_api = ref('');

/**
 * Envia os dados para a API Laravel
 */
const realizar_cadastro = async () => {
    erro_api.value = '';
    
    // Validação simples de senha igual
    if (formulario.value.password !== formulario.value.password_confirmation) {
        erro_api.value = 'As senhas não coincidem.';
        return;
    }

    carregando.value = true;

    try {
        // Tenta cadastrar na rota pública
        const resposta = await axios.post('http://127.0.0.1:8000/api/cadastrar', formulario.value);
        
        // Se deu certo, salva o token e entra
        localStorage.setItem('token_erp', resposta.data.access_token);
        
        alert('Cadastro realizado com sucesso!');
        router.push('/dashboard');

    } catch (erro) {
        // Tratamento de erros vindos do Laravel
        if (erro.response && erro.response.data && erro.response.data.errors) {
            // Pega o primeiro erro da lista (ex: "Email já existe")
            const lista_erros = Object.values(erro.response.data.errors);
            erro_api.value = lista_erros[0][0]; 
        } else if (erro.response && erro.response.data.mensagem) {
            erro_api.value = erro.response.data.mensagem;
        } else {
            erro_api.value = 'Erro ao conectar com o servidor.';
        }
    } finally {
        carregando.value = false;
    }
};
</script>

<template>
    <div class="container_fundo">
        <div class="wrapper_central">
            <Card class="card_custom">
                <template #title>
                    <h2 class="titulo">Criar Conta</h2>
                    <p class="subtitulo">Junte-se ao Nitec ERP</p>
                </template>
                
                <template #content>
                    <Message v-if="erro_api" severity="error" class="msg_erro" :closable="false">
                        {{ erro_api }}
                    </Message>

                    <form @submit.prevent="realizar_cadastro" class="form_grid">
                        
                        <div class="campo_full">
                            <label>Nome Completo</label>
                            <InputText 
                                v-model="formulario.nome_completo" 
                                class="input_full" 
                                required 
                            />
                        </div>

                        <div class="campo_metade">
                            <label>E-mail</label>
                            <InputText 
                                v-model="formulario.email" 
                                type="email" 
                                class="input_full" 
                                required 
                            />
                        </div>

                        <div class="campo_metade">
                            <label>Celular</label>
                            <InputText 
                                v-model="formulario.celular" 
                                placeholder="(00) 00000-0000" 
                                class="input_full" 
                            />
                        </div>

                        <div class="campo_metade">
                            <label>Senha</label>
                            <Password 
                                v-model="formulario.password" 
                                toggleMask 
                                :feedback="true" 
                                promptLabel="Escolha uma senha"
                                weakLabel="Fraca"
                                mediumLabel="Média"
                                strongLabel="Forte"
                                inputClass="input_full" 
                                class="input_full_wrapper" 
                            />
                        </div>

                        <div class="campo_metade">
                            <label>Confirmar Senha</label>
                            <Password 
                                v-model="formulario.password_confirmation" 
                                :feedback="false" 
                                toggleMask 
                                inputClass="input_full" 
                                class="input_full_wrapper" 
                            />
                        </div>

                        <div class="campo_botoes">
                            <Button 
                                type="submit" 
                                label="CRIAR CONTA" 
                                :loading="carregando" 
                                fluid 
                            />
                            
                            <div class="link_login">
                                Já tem conta? <a @click="router.push('/')">Fazer Login</a>
                            </div>
                        </div>
                    </form>
                </template>
            </Card>
        </div>
    </div>
</template>

<style scoped>
.container_fundo {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f3f4f6;
}

.wrapper_central { 
    width: 100%; 
    max-width: 700px; /* Um pouco mais largo para caber 2 colunas */
    padding: 20px; 
}

.card_custom {
    border: none;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.titulo { 
    text-align: center; 
    color: #111827; 
    margin-bottom: 0.5rem; 
}

.subtitulo { 
    text-align: center; 
    color: #6b7280; 
    margin-top: 0; 
}

.form_grid { 
    display: flex; 
    flex-wrap: wrap; 
    gap: 1rem; 
    margin-top: 1rem; 
}

.campo_full { 
    width: 100%; 
    display: flex; 
    flex-direction: column; 
    gap: 5px; 
}

.campo_metade { 
    width: calc(50% - 0.5rem); 
    display: flex; 
    flex-direction: column; 
    gap: 5px; 
}

/* CSS Profundo para garantir que os inputs preencham o espaço */
.input_full { width: 100%; }
.input_full_wrapper { width: 100%; }
:deep(.p-password-input) { width: 100%; }

.campo_botoes { 
    width: 100%; 
    margin-top: 1rem; 
    display: flex; 
    flex-direction: column; 
    gap: 1rem; 
}

.msg_erro { margin-bottom: 1rem; }

.link_login { 
    text-align: center; 
    color: #6b7280;
    font-size: 0.9rem;
}

.link_login a {
    cursor: pointer; 
    color: #4f46e5; 
    font-weight: 600; 
    margin-left: 5px;
}

.link_login a:hover { text-decoration: underline; }

/* Responsividade: Celular fica tudo uma coluna só */
@media (max-width: 600px) {
    .campo_metade { width: 100%; }
}
</style>