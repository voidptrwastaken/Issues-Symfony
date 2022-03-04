<?php

namespace App\Controller;

use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function App\Database\displayIssue;

class DetailsController extends AbstractController
{
    private IssueRepository $repository;

    public function __construct(IssueRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/issues/{id}")
     */
    public function showDetails($id): Response
    {
        $issue = $this->repository->fetchIssue((int)$id);
        return new Response($this->renderView("issues/details.html.twig", ["issue" => $issue, "id" => $id]));
    }
}
