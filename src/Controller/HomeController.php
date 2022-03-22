<?php

namespace App\Controller;

use App\Repository\IssueRepository;
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
    public function showIssues(): Response
    {
        $issues = $this->repository->fetchIssues();
        return new Response($this->renderView("issues/show.html.twig", ["issues" => $issues]));
    }
}
