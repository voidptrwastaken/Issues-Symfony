<?php

namespace App\Controller;

use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewController extends AbstractController
{
    private IssueRepository $repository;

    public function __construct(IssueRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route("/create", methods: ["GET"])]
    public function showCreateIssue(): Response
    {
        return new Response($this->renderView("issues/create.html.twig"));
    }

    #[Route("/create", methods: ["POST"])]
    public function createIssue(): Response
    {
        $this->repository->createIssue($_POST["title"], $_POST["description"]);
        
        return $this->redirectToRoute('app_home_showissues');
    }
}
