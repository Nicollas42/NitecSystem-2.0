<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
// Componentes PrimeVue
import Card from 'primevue/card';
import Button from 'primevue/button';

const router = useRouter();
const usuario = ref({ nome_completo: 'Carregando...', email: '' });

const buscar_dados_usuario = async () => {
    try {
        // CORREÇÃO AQUI: Usando a rota padrão '/user'
        const resposta = await axios.get('/user');
        usuario.value = resposta.data;

    } catch (erro) {
        console.error("Sessão expirada ou erro:", erro);
        // Se der erro 401 ou 404, desloga
        localStorage.removeItem('token_erp');
        router.push('/');
    }
};

const fazer_logout = async () => {
    try {
        await axios.post('/logout');
    } catch (e) {
        // Ignora erro no logout, apenas limpa localmente
    } finally {
        localStorage.removeItem('token_erp');
        router.push('/');
    }
};

onMounted(() => {
    buscar_dados_usuario();
});
</script>

<template>
    <div class="dashboard_container">
        <div class="navbar">
            <h2 class="logo">Nitec ERP</h2>
            <Button label="Sair" icon="pi pi-sign-out" class="p-button-danger p-button-text" @click="fazer_logout" />
        </div>

        <div class="conteudo">
            <Card class="card_boasvindas">
                <template #title>
                    Olá, {{ usuario.nome_completo }}!
                </template>
                <template #content>
                    <p>Você está logado no sistema.</p>
                    <p><strong>Seu E-mail:</strong> {{ usuario.email }}</p>
                    <p><strong>Seu Celular:</strong> {{ usuario.celular || 'Não informado' }}</p>
                </template>
            </Card>
        </div>
    </div>
</template>

<style scoped>
.dashboard_container { min-height: 100vh; background-color: #f8f9fa; }
.navbar { 
    background: white; 
    padding: 1rem 2rem; 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    box-shadow: 0 2px 4px rgba(0,0,0,0.05); 
}
.conteudo { padding: 2rem; max-width: 1200px; margin: 0 auto; }
.card_boasvindas { margin-top: 20px; }
</style>