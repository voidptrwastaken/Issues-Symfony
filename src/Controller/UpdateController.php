<?php

namespace App\Controller;

use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController extends AbstractController
{
    private IssueRepository $repository;

    public function __construct(IssueRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route("issues/update/{id}", methods: ["GET"])]
    public function showUpdateIssue($id): Response
    {
        $issue = $this->repository->fetchIssue((int)$id);
        return new Response($this->renderView("issues/update.html.twig", ["issue" => $issue]));
    }

    #[Route("issues/update/{id}", methods: ["POST"])]
    public function updateIssue($id): Response
    {
        $this->repository->updateIssue((int) $id, $_POST["title"], $_POST["description"], (int)$_POST["severity"]);
        
        return $this->redirectToRoute('app_home_showissues');
    }
}
