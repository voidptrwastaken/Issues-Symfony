<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class SearchController extends AbstractController
{
    private IssueRepository $repository;

    public function __construct(IssueRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route("/search", methods: ["GET"])]
    public function searchIssues(Request $request, PaginatorInterface $paginator): Response
    {
        $query = trim($request->query->get("query"));


        $issuesfromDB = $this->repository->searchIssues($query);

        $issues = $paginator->paginate(
            $issuesfromDB,
            $request->query->getInt('page', 1),
            10
        );

        if ($query === "") {
            return new Response($this->renderView("issues/results.html.twig", ["issues" => $issues, "message" => "Please enter at least one term to search", "count" => ""]));
        }

        return new Response($this->renderView("issues/results.html.twig", ["issues" => $issues, "message" => "Displaying search results for \"" . $query . "\"", "count" => count($issuesfromDB)]));
    }
}
