<?php

/**
 * Database setup script for Facebug
 * 
 * This script helps setting up the database for the Facebug application.
 * It creates the MySQL database if it doesn't exist and runs the migrations.
 */

$basePath = dirname(__DIR__);
require $basePath . '/vendor/autoload.php';

echo "🚀 Iniciando configuração do banco de dados...\n";

// Load environment variables
if (file_exists($basePath . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable($basePath);
    $dotenv->load();
} else {
    die("❌ Arquivo .env não encontrado. Por favor, crie-o primeiro.\n");
}

// Create database connection
try {
    $pdo = new PDO(
        "mysql:host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    
    // Set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $database = $_ENV['DB_DATABASE'];
    echo "📦 Verificando banco de dados '{$database}'...\n";
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}`");
    echo "✅ Banco de dados configurado com sucesso!\n";

} catch (PDOException $e) {
    die("❌ Erro na conexão com MySQL: " . $e->getMessage() . "\n");
}

// Run the migrations
echo "🔄 Executando migrações...\n";
passthru('php ' . $basePath . '/artisan migrate:fresh --force');

// Run the seeders
echo "\n🌱 Populando o banco de dados...\n";
passthru('php ' . $basePath . '/artisan db:seed --force');

echo "\n✨ Configuração do banco de dados concluída com sucesso!\n";
