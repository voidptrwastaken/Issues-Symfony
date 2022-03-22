<?php

namespace App\Controller;

use App\Form\Type\IssueType;
use App\Repository\IssueRepository;
use IssueData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController extends AbstractController
{
    private IssueRepository $repository;

    public function __construct(IssueRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route("issues/update/{id}")]
    public function showUpdateIssue($id, Request $request): Response
    {
        $issue = $this->repository->fetchIssue((int)$id);

        $issueData = new IssueData();

        $issueData->title = $issue->getTitle();
        $issueData->description = $issue->getDescription();
        $issueData->severity = $issue->getSeverity();

        $form = $this->createForm(IssueType::class, $issueData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var IssueData */
            $issueData = $form->getData();

            $this->repository->updateIssue((int) $id, $issueData->title, $issueData->description, $issueData->severity);

            return $this->redirectToRoute('app_home_showissues');
        }

        return new Response($this->renderView("issues/update.html.twig", ["form" => $form->createView(), "issue" => $issue]));
    }
}
