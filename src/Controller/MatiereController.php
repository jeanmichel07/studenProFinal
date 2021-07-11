<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * Class MatiereController
 * @package App\Controller
 * @Route("/setting")
 */
class MatiereController extends AbstractController
{
    private $repository;
    private $entity_manager;

    public function __construct(MatiereRepository $repository, EntityManagerInterface $entity_manager)
    {
        $this->repository = $repository;
        $this->entity_manager = $entity_manager;
    }

    /**
     * @Route("/matiere", name="matiere")
     */
    public function index(): Response
    {
        $all_matiere = $this->repository->findAll();
        return $this->render('matiere/index.html.twig', [
            'matiere' => $all_matiere
        ]);
    }

    /**
     * @param Request $request
     * @Route("/manage-matiere", name="manage-matiere")
     */
    public function manage(Request $request)
    {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $matiere->setStatu(true);
            $this->entity_manager->persist($matiere);
            $this->entity_manager->flush();
            return $this->redirectToRoute('matiere');
        }
        return $this->render('matiere/manage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Matiere $matiere
     * @return RedirectResponse|Response
     * @Route("/edit-matiere/{id}", name="edit-matiere")
     */
    public function edit(Request $request, Matiere $matiere)
    {
        $form_edition = $this->createForm(MatiereType::class, $matiere);
        $form_edition->handleRequest($request);
        if ($form_edition->isSubmitted() and $form_edition->isValid()) {
            $matiere->setStatu(true);
            $this->entity_manager->persist($matiere);
            $this->entity_manager->flush();
            return $this->redirectToRoute('matiere');
        }
        return $this->render('matiere/manage.html.twig', [
            'form' => $form_edition->createView()
        ]);
    }

    /**
     * @Route("/subject/change-state/{id}")
     * @param Request $request
     * @param Matiere $matiere
     * @return Response
     */
    public function changeState(Request $request, Matiere $matiere): Response
    {
        if ($request->isXmlHttpRequest()) {
            $content_request = $request->getContent();
            if (!empty($content_request)) {
                $params = json_decode($content_request, true);
                $matiere->setStatu($params['value']);
                $this->entity_manager->persist($matiere);
                $this->entity_manager->flush();
                return $this->json(['message' => 'success', 'result' => $matiere->getStatu()], Response::HTTP_OK);
            }

            return $this->json(['message' => 'La requête est vide'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        throw new RouteNotFoundException();
    }
}
