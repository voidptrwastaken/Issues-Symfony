<?php

namespace App\Controller;

use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function App\Database\deleteIssue;
use function App\Database\displayIssue;

class NewController extends AbstractController
{
    #[Route("/create", methods: ["GET"])]
    public function showCreateIssue(): Response
    {
        return new Response($this->renderView("issues/create.html.twig"));
    }

    #[Route("/create", methods: ["POST"])]
    public function createIssue(): Response
    {
        $repository = new IssueRepository();
        $repository->createIssue($_POST["title"], $_POST["description"]);
        
        return $this->redirectToRoute('app_home_showissues');
    }
}
