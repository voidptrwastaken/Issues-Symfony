<?php

namespace App\Repository;

use PDO;

class IssueRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("sqlite:".__DIR__."/../../var/issues.sq3");
    }

    public function createIssue(string $title, string $description)
    {
        $query = "INSERT INTO issue (title, description) VALUES (:title, :description)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":title", $title);
        $statement->bindValue(":description", $description);
        $statement->execute();
    }
}