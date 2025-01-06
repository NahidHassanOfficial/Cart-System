<?php
class Database
{
    private $conn = null;

    public function connect()
    {
        if ($this->conn === null) {
            try {
                // Connect without database first
                $this->conn = new PDO(
                    "mysql:host=" . Config::DB_HOST,
                    Config::DB_USER,
                    Config::DB_PASS
                );

                // Check and create database if needed
                $this->initializeDatabase();

                // connect with database
                $this->conn = new PDO(
                    "mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME,
                    Config::DB_USER,
                    Config::DB_PASS
                );

                // set the PDO error mode to exception
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                throw new Exception("Connection failed: ");
            }
        }
        return $this->conn;
    }

    private function initializeDatabase()
    {
        try {
            $query =
            $this->conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . Config::DB_NAME . "' ");

            //create db if fetch returns false
            if (!$query->fetch()) {
                $this->createDatabase();
            }
        } catch (PDOException $e) {
            throw new Exception("Error checking or initializing the database: ");
        }
    }

    private function createDatabase()
    {
        try {
            $this->conn->exec("CREATE DATABASE " . Config::DB_NAME);
            $this->conn->exec("USE " . Config::DB_NAME);
            $this->createTables();
        } catch (PDOException $e) {
            throw new Exception("Error creating database: ");
        }
    }

    private function createTables()
    {
        $tables = [
            "CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL,
                category VARCHAR(50) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            "CREATE TABLE IF NOT EXISTS product_variants (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                variant_name VARCHAR(50) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                image_path VARCHAR(255) NOT NULL,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
            )",
        ];

        try {
            foreach ($tables as $tableQuery) {
                $this->conn->exec($tableQuery);
            }
        } catch (PDOException $e) {
            throw new Exception("Error creating tables: ");
        }
    }
}
