<?php

namespace App\Repository;

use App\Model\Issue;
use PDO;

class IssueRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
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
        $issue = $this->fetchIssue($id);

        if($issue->getResolutionStatus() === 1)
        {
            return false;
        }

        $query = "UPDATE issue SET title=:title, description=:description, modificationDate=:modificationDate, severity=:severity WHERE id=$id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":title", $title);
        $statement->bindValue(":description", $description);
        $statement->bindValue(":modificationDate", time());
        $statement->bindValue(":severity", $severity);
        $statement->execute();

        return true;
    }

    public function deleteIssue(int $id)
    {
        $query = "DELETE FROM issue WHERE id=:id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public function solveIssue(int $id)
    {
        $query = "UPDATE issue SET isSolved=:status, resolutionDate=:rDate WHERE id=$id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":status", 1);
        $statement->bindValue(":rDate", time());
        $statement->execute();
    }

    public function fetchIssues(): array
    {
        $query = 'SELECT * FROM issue';
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        $issuesArray = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $issues = [];

        foreach ($issuesArray as $index => $issue) {
            $issueObject = new Issue($issue["id"], $issue["title"], $issue["description"], $issue["submissionDate"], $issue["modificationDate"], $issue["severity"], $issue["isSolved"], $issue["resolutionDate"]);

            array_push($issues, $issueObject);
        }

        return $issues;
    }

    public function searchIssues(string $searchQuery): array
    {
        $tokens = preg_split("/\s+/", $searchQuery);

        $issuesArray = [];

        $titleQuery = "";
        $descriptionQuery = "";

        foreach ($tokens as $index => $token) {

            $titleQuery .= ($titleQuery === '' ? ' ' : ' AND ') . "title LIKE :term_" . $index . " ";

            $descriptionQuery .= ($descriptionQuery === '' ? ' ' : ' AND ') . "description LIKE :term_" . $index . " ";
        }

        $query = 'SELECT * FROM issue WHERE (' . $titleQuery  . 'ESCAPE "\") OR (' . $descriptionQuery . 'ESCAPE "\")';
        $statement = $this->pdo->prepare($query);

        foreach ($tokens as $index => $token) {
            $token = str_replace(["%", "_"], ["\%", "\_"], $token);
            $statement->bindValue(":term_" . $index, "%" . $token . "%");
        }
        $statement->execute();

        $issuesArray = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $issues = [];

        foreach ($issuesArray as $issue) {
            $issueObject = new Issue($issue["id"], $issue["title"], $issue["description"], $issue["submissionDate"], $issue["modificationDate"], $issue["severity"], $issue["isSolved"], $issue["resolutionDate"]);

            array_push($issues, $issueObject);
        }

        return $issues;
    }

    public function fetchIssue(int $id): Issue
    {

        $query = 'SELECT * FROM issue WHERE id=:id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":id", $id);
        $statement->execute();

        $issue = $statement->fetch(PDO::FETCH_ASSOC);

        $issueObject = new Issue($issue["id"], $issue["title"], $issue["description"], $issue["submissionDate"], $issue["modificationDate"], $issue["severity"], $issue["isSolved"], $issue["resolutionDate"]);
        return $issueObject;
    }
}
