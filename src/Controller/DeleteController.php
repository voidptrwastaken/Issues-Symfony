<?php

namespace App\Controller;

use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function App\Database\deleteIssue;
use function App\Database\displayIssue;

class DeleteController extends AbstractController
{
    private IssueRepository $repository;

    public function __construct(IssueRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route("/issues/delete/{id}", methods: ["GET"])]
    public function showDeleteIssue($id): Response
    {
        $issue = $this->repository->fetchIssue($id);

        if ($issue->getResolutionStatus() === 1)
        {
            return new Response($this->renderView("issues/delete.html.twig", ["issue" => $issue]));
        }
        
        return new Response($this->renderView("issues/delete.html.twig", ["issue" => $issue]));
    }

    #[Route("/issues/delete/{id}", methods: ["POST"])]
    public function deleteIssue($id): Response
    {
        $issue = $this->repository->fetchIssue($id);

        if ($issue->getResolutionStatus() === 1)
        {
            return new Response($this->renderView("issues/delete.html.twig", ["issue" => $issue]));
        }

        $this->repository->deleteIssue($id);

        return $this->redirectToRoute('app_home_showissues');
    }

    #[Route("/issues/forceDelete/{id}", methods: ["POST"])]
    public function forceDeleteIssue($id): Response
    {
        $issue = $this->repository->fetchIssue($id);

        if ($issue->getResolutionStatus() === 1)
        {
            return new Response($this->renderView("issues/delete.html.twig", ["issue" => $issue]));
        }

        $this->repository->deleteIssue($id);

        return $this->redirectToRoute('app_home_showissues');
    }
}
