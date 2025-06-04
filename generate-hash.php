<?php
// Script simple para generar un hash de contraseña compatible con Laravel

// La contraseña que quieres hashear
$password = 'Dios1234';

// Generar hash usando el algoritmo de Laravel
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

echo "Contraseña original: $password\n";
echo "Hash generado: $hash\n";
