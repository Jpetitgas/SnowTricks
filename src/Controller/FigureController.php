<?php

namespace App\Controller;

use DateTime;
use App\Entity\Figure;
use App\Entity\Comment;
use App\Entity\Image;
use App\Form\FigureType;
use App\Form\CommentType;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Date;

class FigureController extends AbstractController
{
    /**
     * @Route("/figure/{slug}", name="figure_show", priority=-1)
     */
    public function show($slug, Request $request, Security $securit, FigureRepository $figureRepository, EntityManagerInterface $em): Response
    {
        $figure = $figureRepository->findOneBy(
            [
                'slug' => $slug
            ]
        );
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
            $em->persist($comment);
            $em->flush();
            return $this->RedirectToRoute('figure_show', ['slug' => $slug]);
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
    public function create(Request $request, Security $securit, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $figure = new Figure;
        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);
        $user = $securit->getUser();
        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $img = new Image();
                $img->setName($fichier);
                $img->setMain(false);
                $figure->addImage($img);
            }

            $figure->setWriter($user);
            $figure->setSlug(strtolower($slugger->slug($figure->getName())));
            $figure->setDate(new DateTime());
            $figure->setDateMod(new DateTime());
            $em->persist($figure);
            $em->flush();
            return $this->RedirectToRoute('main');
        }

        $fromView = $form->CreateView();

        return $this->render('figure/create.html.twig', [
            'formView' => $fromView
        ]);
    }

    /**
     * @Route("/figure/edit/{slug}", name="figure_edit", priority=-1)
     */
    public function edit($slug, Request $request, Security $securit, FigureRepository $figureRepository, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $figure = $figureRepository->findOneBy(
            [
                'slug' => $slug
            ]
        );
        if (!$figure) {
            throw $this->createNotFoundException("Cette figure n'existe pas");
        }

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        $user = $securit->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $img = new Image();
                $img->setName($fichier);
                $img->setMain(false);
                $figure->addImage($img);
            }

            $figure->setWriter($user);
            $figure->setSlug(strtolower($slugger->slug($figure->getName())));
            $figure->setDate(new DateTime());
            $figure->setDateMod(new DateTime());

            $em->flush();
            return $this->RedirectToRoute('figure_show', ['slug' => $slug]);
        }

        $fromView = $form->createView();

        return $this->render('figure/edit.html.twig', [
            'figure' => $figure,
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
