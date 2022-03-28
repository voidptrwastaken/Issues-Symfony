<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private IssueRepository $repository;

    public function __construct(IssueRepository $repository)
    {
        $this->repository = $repository;
    }
    
    #[Route("/search", methods: ["GET"])]
    public function searchIssues(Request $request): Response
    {
        $issues = $this->repository->searchIssues($request->query->get("query"));
        return new Response($this->renderView("issues/show.html.twig", ["issues" => $issues]));
    }
}
