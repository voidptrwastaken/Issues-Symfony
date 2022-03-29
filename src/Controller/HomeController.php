<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\IssueRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private IssueRepository $repository;

    public function __construct(IssueRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @Route("/home")
     */
    public function showIssues(Request $request, PaginatorInterface $paginator): Response
    {
        $issuesfromDB = $this->repository->fetchIssues();
        $issues = $paginator->paginate(
            $issuesfromDB,
            $request->query->getInt('page', 1),
            10
        );
        return new Response($this->renderView("issues/show.html.twig", ["issues" => $issues, "count" => count($issuesfromDB)]));
    }
}
