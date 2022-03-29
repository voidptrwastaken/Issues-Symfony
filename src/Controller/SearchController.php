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
        $query = $request->query->get("query");
        if($query === "" || preg_match("/\s+[^a-z]/", $query))
        {
            $allIssues = $this->repository->fetchIssues();
            return new Response($this->renderView("issues/show.html.twig", ["issues" => $allIssues, "message" => "Please enter at lease one term to search"]));
        }
        $issues = $this->repository->searchIssues($query);
        
        if(count($issues) === 0)
        {
            return new Response($this->renderView("issues/show.html.twig", ["issues" => $issues, "message" => "No results for \"" . $query . "\""]));
        }
        return new Response($this->renderView("issues/show.html.twig", ["issues" => $issues, "message" => "Displaying search results for \"" . $query . "\""]));
    }
}
