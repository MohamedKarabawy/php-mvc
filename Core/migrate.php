<?php

namespace Core;

require_once __DIR__ . '/Database.php';

use Core\Database;

if (!function_exists('env')) 
{
    function env($key, $default = '')
    {
        return $_ENV[$key] ?? getenv($key) ?: $default;
    }
}

$action = $argv[1] ?? null;

if (!in_array($action, ['migrate', 'drop'])) 
{
    die("Usage: php Core/migrate.php [migrate|drop]\n");
}

try 
{
    $pdo = Database::connect();
} 
catch (\PDOException $e) 
{
    die("Connection Failure: " . $e->getMessage() . "\n");
}

$baseDir = dirname(__DIR__);

if ($action === 'migrate') 
{
    executeQueries($pdo, $baseDir . '/App/Migrations/up', 'migrate');
} 
elseif ($action === 'drop') 
{
    executeQueries($pdo, $baseDir . '/App/Migrations/down', 'drop');
}

function executeQueries(\PDO $pdo, string $folderPath, string $actionType)
{
    if (!is_dir($folderPath)) 
    {
        die("Error: Folder does not exist at $folderPath\n");
    }

    $files = glob($folderPath . '/*.sql');

    if (empty($files)) 
    {
        die("No SQL files found in: " . basename($folderPath) . "\n");
    }

    foreach ($files as $file) 
    {
        $fileName = basename($file);
        $sql = file_get_contents($file);

        try 
        {
            $pdo->exec($sql);
            echo $actionType === 'migrate'
                ? "SUCCESS: Executed $fileName (Table created)\n"
                : "SUCCESS: Executed $fileName (Table dropped)\n";
        } 
        catch (\PDOException $e) 
        {
            if ($e->getCode() == '42S01' || str_contains($e->getMessage(), 'already exists')) 
            {
                echo "FAILURE: Cannot migrate $fileName. Table already exists in the database!\n";
            }
            elseif ($e->getCode() == '42S02' || str_contains($e->getMessage(), 'Unknown table')) 
            {
                echo "FAILURE: Cannot drop $fileName. Table does not exist in the database!\n";
            } 
            else 
            {
                echo "ERROR executing $fileName: " . $e->getMessage() . "\n";
            }
        }
    }
}
