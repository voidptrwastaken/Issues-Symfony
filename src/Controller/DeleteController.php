<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function App\Database\deleteIssue;
use function App\Database\displayIssue;

class DeleteController extends AbstractController
{
    #[Route("/issues/delete/{id}", methods: ["GET", "HEAD"])]
    public function showDeleteIssue($id): Response
    {
        $databasePath = __DIR__ . "/../../var/issues.json";
        $issue = displayIssue($id, $databasePath);

        if ($issue == null) {
            throw $this->createNotFoundException("The requested issue (" . (int)$id + 1 . ") was not found");
        }
        return new Response($this->renderView("issues/delete.html.twig", ["issue" => $issue, "id" => $id + 1]));
    }

    #[Route("/issues/delete/{id}", methods: ["POST", "HEAD"])]
    public function deleteIssue($id): Response
    {
        $databasePath = __DIR__ . "/../../var/issues.json";
        $issue = displayIssue($id, $databasePath);

        if ($issue == null) {
            return new Response($this->renderView("issues/error.html.twig", ["id" => (int)$id], 404));
        }

        deleteIssue($id, $databasePath);

        return $this->redirectToRoute('app_home_showissues');
    }

    #[Route("/issues/forceDelete/{id}", methods: ["GET", "HEAD"])]
    public function forceDeleteIssue($id): Response
    {
        $databasePath = __DIR__ . "/../../var/issues.json";
        $issue = displayIssue($id, $databasePath);

        if ($issue == null) {
            return new Response($this->renderView("issues/error.html.twig", ["id" => (int)$id], 404));
        }

        elseif ($issue->getResolutionStatus() === true)
        {
            return new Response($this->renderView("issues/delete.html.twig", ["issue" => $issue, "id" => $id + 1]));
        }

        deleteIssue($id, $databasePath);

        return $this->redirectToRoute('app_home_showissues');
    }
}
