<?php


class Database
{

    public static function getConnection()
    {

        return mysqli_connect("localhost", "root", "Cra5hLoy@ale", "telegram");
    }

}
