<?php
// Script para verificar a saúde dos arquivos do ERP

$arquivos_essenciais = [
    // Backend
    'backend/.env',
    'backend/app/Models/User.php',
    'backend/app/Http/Controllers/AuthController.php',
    'backend/routes/api.php',
    'backend/database/seeders/UsuarioSeeder.php',
    
    // Frontend
    'frontend/package.json',
    'frontend/src/main.js',
    'frontend/src/App.vue',
    'frontend/src/router/index.js',
    'frontend/src/views/LoginView.vue',
];

echo "--- INICIANDO VERIFICAÇÃO DO ERP ---\n\n";

$tem_erro = false;

foreach ($arquivos_essenciais as $arquivo) {
    if (file_exists($arquivo)) {
        echo "[OK] Encontrado: $arquivo\n";
    } else {
        echo "[X]  FALTANDO:   $arquivo <--- ATENÇÃO AQUI\n";
        $tem_erro = true;
    }
}

echo "\n--------------------------------------\n";
if ($tem_erro) {
    echo "RESULTADO: Faltam arquivos! Verifique a lista acima.\n";
} else {
    echo "RESULTADO: Estrutura de arquivos parece correta!\n";
}