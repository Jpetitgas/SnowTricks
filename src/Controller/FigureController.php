<?php

namespace App\Controller;

use DateTime;
use App\Entity\Figure;
use App\Entity\Comment;
use App\Form\FigureType;
use App\Form\CommentType;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{
    /**
     * @Route("/figure/{slug}", name="figure_show", priority=-1)
     */
    public function show($slug, Request $request, Security $securit, FigureRepository $figureRepository, EntityManagerInterface $em): Response
    {
        $figure = $figureRepository->findOneBy([
            'slug' => $slug
        ]);
        if (!$figure) {
            throw $this->createNotFoundException("Cette figure n'existe pas");
        }

        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $user = $securit->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setDate(new DateTime())
                ->setWriter($user)
                ->setFigure($figure);
            $em->flush();
        }

        $fromView = $form->createView();

        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'formView' => $fromView
        ]);
    }
    /**
     * @Route("/figure/create", name="figure_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $figure = new Figure;
        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //$figure->;
            $em->persist($figure);
            $em->flush();
            return $this->RedirectToRoute('figure_show');
        }

        $fromView = $form->CreateView();

        return $this->render('figure/create.html.twig', [
            'formView' => $fromView
        ]);
    }
    /**
     * @Route("/figure/delete/{slug}", name="figure_delete")
     */
    public function delete($slug, FigureRepository $figureRepository, EntityManagerInterface $em): Response
    {
        $figure = $figureRepository->findOneBy([
            'slug' => $slug
        ]);
        if (!$figure) {
            throw $this->createNotFoundException("Cette figure n'existe pas");
        }

        $em->remove($figure);
        $em->flush();

        return $this->RedirectToRoute('main');
    }
}
