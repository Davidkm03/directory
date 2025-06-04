<?php
// Configuración de conexión manual (reemplaza con tus datos)
$servidor = "localhost";
$usuario = "zkutpiez_dental"; // Reemplaza con tu usuario de base de datos
$contrasena = ""; // Reemplaza con tu contraseña
$basedatos = "zkutpiez_dental"; // Reemplaza con tu nombre de base de datos

// Crear conexión
$conn = new mysqli($servidor, $usuario, $contrasena, $basedatos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Hash generado para la contraseña "Dios1234"
$password_hash = '$2y$12$mFZ/gmjU25O67EGK3PEI3eUi.IbhNdNX5ef2/AvKBGyTYCDwf.nd2';

// SQL para insertar
$sql = "INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at) 
        VALUES ('Administrador', 'davidkm0393@gmail.com', '$password_hash', 'admin', NOW(), NOW(), NOW())";

if ($conn->query($sql) === TRUE) {
    echo "Usuario administrador creado exitosamente";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar conexión
$conn->close();
?>
