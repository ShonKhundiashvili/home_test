<?php
//Create class database as required 
class Database
{
    //Creating the variables for database 
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;

    //Constructor that will be called once I call the database instance
    public function __construct($host, $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        //Connect to mysql with host username and password (connection variable will hold the connection)
        $this->connection = new mysqli($this->host, $this->username, $this->password);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        } else {
            echo "Connected to the database.\n";
        }

        //Creating database that I called data
        $sql = "CREATE DATABASE IF NOT EXISTS data";

        //Checking if the query execution is true and if data database already exists if not creating else not creating
        if ($this->connection->query($sql) === TRUE) {
            if ($this->connection->affected_rows > 0) {
                echo "Database 'data' created successfully.\n";
            } else {
                echo "Database 'data' already exists.\n";
            }
        } else {
            echo "Error creating database: " . $this->connection->error . "\n";
        }

        //Connect again and now to the created database 'data'
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die("Connection to database failed: " . $this->connection->connect_error);
        } else {
            echo "Database data is ready to use\n";
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function createTable($table, $fields)
    {
        $query = "CREATE TABLE IF NOT EXISTS $table ($fields)";
        if ($this->connection->query($query)) {
            echo "Table '$table' created successfully.\n";
        } else {
            echo "Error creating table '$table': " . $this->connection->error . "\n";
        }
    }

    public function select($table, $columns = "*", $condition = "")
    {
        $query = "SELECT $columns FROM $table";
        if (!empty($condition)) {
            $query .= " WHERE $condition";
        }
        $result = $this->connection->query($query);
        $rows = array();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo "SELECT operation successful.\n";
        } else {
            echo "Error executing SELECT: " . $this->connection->error . "\n";
        }
        return $rows;
    }

    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $query = "INSERT INTO $table ($columns) VALUES ($values)";
        if ($this->connection->query($query)) {
            echo "INSERT operation successful into table: $table \n";
        } else {
            echo "Error executing INSERT: " . $this->connection->error . "\n";
        }
    }

    public function delete($table, $condition)
    {
        $query = "DELETE FROM $table WHERE $condition";
        if ($this->connection->query($query)) {
            echo "DELETE operation successful.\n";
        } else {
            echo "Error executing DELETE: " . $this->connection->error . "\n";
        }
    }

    public function update($table, $data, $condition)
    {
        $updates = array();
        foreach ($data as $key => $value) {
            $updates[] = "$key = '$value'";
        }
        $updates = implode(", ", $updates);
        $query = "UPDATE $table SET $updates WHERE $condition";
        if ($this->connection->query($query)) {
            echo "UPDATE operation successful.\n";
        } else {
            echo "Error executing UPDATE: " . $this->connection->error . "\n";
        }
    }

    public function close()
    {
        $this->connection->close();
    }
}
