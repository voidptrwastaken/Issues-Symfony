<?php

namespace App\Controller;

use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SolveController extends AbstractController
{
    private IssueRepository $repository;

    public function __construct(IssueRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route("issues/solve/{id}", methods: ["POST"])]
    public function solveIssue($id): Response
    {
        $issue = $this->repository->fetchIssue((int) $id);

        if ($issue->getResolutionStatus() === 1)
        {
            return new Response($this->renderView("issues/delete.html.twig", ["issue" => $issue]));
        }

        $this->repository->solveIssue((int) $id);
        return $this->redirectToRoute('app_home_showissues');
    }
}
