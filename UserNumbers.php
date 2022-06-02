<?php

require_once(__DIR__ . "/Model.php");

class UserNumbers extends Model
{
    public $chat_id;
    public $first_number;
    public $sum;

    public function create(string $chat_id)
    {
        $whatInsert = "($chat_id)";
        $values = "(chat_id)";

        $mainQuery = "INSERT INTO user_numbers$values VALUES$whatInsert;";
        var_dump($mainQuery);
        $this->databaseConnection->query($mainQuery);

    }

    public function update(string $var, int $data)
    {
        $query = "UPDATE user_numbers SET $var=? WHERE chat_id=$this->chat_id;";
        $params = ["s", [$data]];
        $this->query($query, $params);

        echo $this->toJson($this->find($this->id));
    }

    public function find(string $chat_id, bool $assoc = false)
    {
        $query = "SELECT * FROM user_numbers WHERE chat_id=$chat_id;";
        $result = $this->databaseConnection->query($query);
        var_dump($this->databaseConnection);
        if ($assoc)
            return mysqli_fetch_assoc($result);
        else
            return mysqli_fetch_object($result, UserNumbers::class);
    }
    public function delete()
    {
        $query = "DELETE FROM user_numbers where chat_id=$this->chat_id;";

        $this->databaseConnection->query($query);

    }
}