<?php

namespace App\Repository;

use App\Model\Issue;
use PDO;
use Symfony\Bundle\MakerBundle\Str;

class IssueRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("sqlite:" . __DIR__ . "/../../var/issues.sq3");
    }

    public function createIssue(string $title, string $description, int $severity)
    {
        $query = "INSERT INTO issue (title, description, submissionDate, modificationDate, severity, isSolved, resolutionDate) VALUES (:title, :description, :submissionDate, :modificationDate, :severity, :isSolved, :resolutionDate)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":title", $title);
        $statement->bindValue(":description", $description);
        $statement->bindValue(":submissionDate", time());
        $statement->bindValue(":modificationDate", null);
        $statement->bindValue(":severity", $severity);
        $statement->bindValue(":isSolved", 0);
        $statement->bindValue(":resolutionDate", null);
        $statement->execute();
    }

    public function updateIssue(int $id, string $title, string $description, int $severity)
    {
        $query = "UPDATE issue SET title=':title', description=':description', modificationDate=':modificationDate', severity=':severity' WHERE id=$id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":title", $title);
        $statement->bindValue(":description", $description);
        $statement->bindValue(":modificationDate", time());
        $statement->bindValue(":severity", $severity);
        $statement->execute();
    }

    public function fetchIssues(): array
    {
        $query = 'SELECT * FROM issue';
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        $issuesArray = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $issues = [];

        foreach($issuesArray as $index => $issue)
        {
            $issueObject = new Issue($issue["id"], $issue["title"], $issue["description"], $issue["submissionDate"], $issue["modificationDate"], $issue["severity"], $issue["isSolved"], $issue["resolutionDate"]);
        
            array_push($issues, $issueObject);
        }

        return $issues;
    }

    public function fetchIssue(int $id)
    {
        $id --;
        
        $issues = $this->fetchIssues();

        if (!array_key_exists($id, $issues)) {
            return null;
        }

        $issue = $issues[$id]; 
        return $issue;
    }
}
