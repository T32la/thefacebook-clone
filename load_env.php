<?php
/**
 * Carga variables de entorno desde archivo .env
 * Sin dependencias externas
 */
function loadEnv($path = __DIR__) {
    $envFile = $path . '/.env';
    
    // Verificar si existe el archivo
    if (!file_exists($envFile)) {
        die("Error: .env file not found. Please copy .env.example to .env and configure it.");
    }
    
    // Leer el archivo línea por línea
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Ignorar comentarios y líneas vacías
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        // Separar clave y valor
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remover comillas si existen
            $value = trim($value, '"\'');
            
            // Establecer variable de entorno
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

/**
 * Obtener variable de entorno con valor por defecto opcional
 */
function env($key, $default = null) {
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    
    $value = getenv($key);
    return $value !== false ? $value : $default;
}
?>