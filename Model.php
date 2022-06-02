<?php

require_once(__DIR__ . "/Database.php");

class Model extends Database
{
    /**
     * @var mysqli
     */
    protected $databaseConnection;

    public function __construct()
    {
        $this->databaseConnection = Database::getConnection();
    }

    public function query(string $query, array $params)
    {
        $exec_query = $this->databaseConnection->stmt_init();
        $exec_query->prepare($query);
        $exec_query->bind_param($params[0], ...$params[1]);
        $exec_query->execute();
        $exec_query->get_result();
    }
}