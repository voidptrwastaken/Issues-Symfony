<?php

namespace App\Database;

use App\Model\Issue;

function validateInput($_title, $_description): array
{
    $status = [];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (array_key_exists("title", $_POST) && is_string(($_POST["title"]))) {
            $_title = $_POST["title"];
        }
        if (array_key_exists("description", $_POST) && is_string(($_POST["description"]))) {
            $_description = $_POST["description"];
        }

        if ($_title === "") {
            $status = ["status" => false, "statusMessage" => "Title cannot be emtpy!"];
            return $status;
        } elseif ($_description === "") {
            $status = ["status" => false, "statusMessage" => "Description cannot be emtpy!"];
            return $status;
        } else {
            $status = ["status" => true, "statusMessage" => "Inptut has been validated"];
            return $status;
        }
    } else {
        $status = ["status" => false, "statusMessage" => "Invalid request method"];
    }
}

function validateIssueIndex($issueIndex): bool
{
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        if (array_key_exists("id", $_GET) && is_string(($_GET["id"]))) {
            return true;
        }
    }
    return false;
}

function createIssue($title, $description, $databasePath): void
{
    //$issue = [
    //    "title" => $title,
    //    "description" => $description,
    //    "submissionDate" => time(),
    //    "modificationDate" => null,
    //    "isSolved" => false,
    //    "resolutionDate" => null
    //];

    $issue = new Issue(0, $title, $description, time(), null, 0, 0, null);

    $issues = [];
    if (file_exists($databasePath)) {
        $issuesAsJson = file_get_contents($databasePath);
        $issues = json_decode($issuesAsJson, true);
    }
    array_push($issues, $issue->toArray());

    $issuesAsJson = json_encode($issues, JSON_PRETTY_PRINT);

    file_put_contents($databasePath, $issuesAsJson);
}

function readIssues($databasePath): array
{
    $issues = [];

    if (file_exists($databasePath)) {
        $issuesAsJson = file_get_contents($databasePath);
        $issuesRaw = json_decode($issuesAsJson, true);

        foreach($issuesRaw as $index => $issue)
        {
            $issueObject = new Issue($issue["id"], $issue["title"], $issue["description"], $issue["submissionDate"], $issue["modificationDate"], $issue["severity"], $issue["isSolved"], $issue["resolutionDate"]);
        
            array_push($issues, $issueObject);
        }
    }

    return $issues;
}

function updateIssue($title, $description, $issueIndex, $databasePath): void
{
    $issues = readIssues($databasePath);

    $issueToUpdate = $issues[$issueIndex];

    $issueToUpdate = $issueToUpdate->updateSelf($title, $description);

    foreach ($issues as $index => $issueObj) {
        $issues[$index] = $issueObj->toArray();
    }

    $issues[$issueIndex] = $issueToUpdate->toArray();

    $issuesAsJson = json_encode($issues, JSON_PRETTY_PRINT);

    file_put_contents($databasePath, $issuesAsJson);
}

function displayIssue($issueIndex, $databasePath)
{
    $issues = readIssues($databasePath);

    if(!array_key_exists($issueIndex, $issues))
    {
        return null;
    }
    return $issues[$issueIndex];
}

function deleteIssue($issueIndex, $databasePath): void
{
    $issues = readIssues($databasePath);

    foreach ($issues as $index => $issueObj) {
        $issues[$index] = $issueObj->toArray();
    }

    unset($issues[$issueIndex]);

    $issuesAsJson = json_encode($issues);

    file_put_contents($databasePath, $issuesAsJson);
}

function solveIssue($issueIndex, $databasePath): void
{
    $issues = readIssues($databasePath);

    $issueToSolve = $issues[$issueIndex];

    $issueToSolve->markAsSolved();

    foreach ($issues as $index => $issueObj) {
        $issues[$index] = $issueObj->toArray();
    }

    $issues[$issueIndex] = $issueToSolve->toArray();

    $issuesAsJson = json_encode($issues, JSON_PRETTY_PRINT);

    file_put_contents($databasePath, $issuesAsJson);
}
