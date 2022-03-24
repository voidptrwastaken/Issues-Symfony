<?php

namespace App\Controller;

use App\Form\Type\IssueType;
use App\Repository\IssueRepository;
use IssueData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Validator\Constraints\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewController extends AbstractController
{
    private IssueRepository $repository;

    public function __construct(IssueRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route("/create")]
    public function createIssue(Request $request): Response
    {
        $issueData = new IssueData();

        $form = $this->createForm(IssueType::class, $issueData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var IssueData */
            $issueData = $form->getData();

            $this->repository->createIssue($issueData->title, $issueData->description, $issueData->severity);

            return $this->redirectToRoute('app_home_showissues');
        }
        return new Response($this->renderView("issues/create.html.twig", ["form" => $form->createView()]));
    }
}
