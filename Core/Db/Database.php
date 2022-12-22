<?php

namespace App\Core\Db;

use App\Core\Application;

class Database
{

    public \PDO $pdo;


public function __construct(array $config)
{
    $dsn = $config['dsn'] ?? '';
    $user = $config['user'] ?? '';
    $password = $config['password'] ?? '';
    $this->pdo = new \PDO($dsn, $user , $password);
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

}


    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR . '/migrations/');

        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration)
        {
            if($migration === '.' || $migration === '..')
            {
                continue;
            }

            require_once Application::$ROOT_DIR . '/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);

            $instance = new $className();
            $this->log("Application de la migration $migration" . PHP_EOL);
            $instance->up();
            $newMigrations[] = $migration;
        }

        if(!empty($newMigrations))
        {
            $this->saveMigrations($newMigrations);
        }else {   $this->log("toute les migrations ont étaient appliqué" . PHP_EOL);}
    }

    public function saveMigrations(array $migrations)
    {
        $str = implode(",", array_map(fn($m) => "('$m')" , $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $statement->execute();

    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    protected function log($message)
    {
        echo '['.date('d-m-Y H:i:s').'] - '.$message.PHP_EOL;
    }

    public function prepare($QUERY)
    {
        return $this->pdo->prepare($QUERY);
    }
}