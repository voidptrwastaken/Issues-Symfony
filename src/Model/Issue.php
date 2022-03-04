<?php

namespace App\Model;

class Issue
{
    private int $id;
    private string $title;
    private string $description;
    private int $submissionDate;
    private ?int $modificationDate = null;
    private int $severity;
    private int $isSolved;
    private ?int $resolutionDate = null;

    public function __construct(
        int $id,
        string $title,
        string $description,
        int $submissionDate,
        ?int $modificationDate,
        int $severity,
        int $isSolved,
        ?int $resolutionDate
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->submissionDate = $submissionDate;
        $this->modificationDate = $modificationDate;
        $this->severity = $severity;
        $this->isSolved = $isSolved;
        $this->resolutionDate = $resolutionDate;
    }

    public function getID(): int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getSubmissionDate(): int
    {
        return $this->submissionDate;
    }
    public function getModificationDate(): ?int
    {
        return $this->modificationDate;
    }
    public function getSeverity(): int
    {
        return $this->severity;
    }
    public function getResolutionStatus(): int
    {
        return $this->isSolved;
    }
    public function getResolutionDate(): ?int
    {
        return $this->resolutionDate;
    }

    public function updateSelf(string $title, string $description): void
    {
        $this->title = $title;
        $this->description = $description;
        $this->modificationDate = time();
    }

    public function markAsSolved(): void
    {
        $this->isSolved = true;
        $this->resolutionDate = time();
    }

    public function toArray(): array
    {
        $issue = [
            "title" => $this->title,
            "description" => $this->description,
            "submissionDate" => $this->submissionDate,
            "modificationDate" => $this->modificationDate,
            "isSolved" => $this->isSolved,
            "resolutionDate" => $this->resolutionDate
        ];

        return $issue;
    }
}
