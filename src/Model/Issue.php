<?php

namespace App\Model;

class Issue
{
    private string $title;
    private string $description;
    private int $submissionDate;
    private ?int $modificationDate = null;
    private bool $isSolved = false;
    private ?int $resolutionDate = null;
    
    public function __construct(
        string $title,
        string $description,
        int $submissionDate,
        ?int $modificationDate,
        bool $isSolved,
        ?int $resolutionDate
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->submissionDate = $submissionDate;
        $this->modificationDate = $modificationDate;
        $this->isSolved = $isSolved;
        $this->resolutionDate = $resolutionDate;
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
    public function getResolutionStatus(): bool
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
