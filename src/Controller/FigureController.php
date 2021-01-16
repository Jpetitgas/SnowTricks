<?php

namespace App\Controller;

use DateTime;
use App\Entity\Figure;
use App\Entity\Comment;
use App\Entity\Image;
use App\Form\FigureType;
use App\Form\CommentType;
use App\Image\MainImage;
use App\Image\UpLoadImages;
use App\Repository\CommentRepository;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FigureController extends AbstractController
{
    protected $mainImage;
    protected $uploadImages;


    public function __construct(MainImage $mainImage, UpLoadImages $upLoadImages)
    {
        $this->mainImage = $mainImage;
        $this->uploadImages = $upLoadImages;
    }


    /**
     * @Route("/figure/{slug}", name="figure_show", priority=-1)
     */
    public function show($slug, Request $request,CommentRepository $commentRepository, Security $securit, FigureRepository $figureRepository, EntityManagerInterface $em): Response
    {
        $figure = $figureRepository->findOneBy(
            [
                'slug' => $slug
            ]
        );
        
        $limit = 10;
        $page = (int)$request->query->get("page", 1);
        $comments = $commentRepository->getPaginationComments($figure, $page, $limit);
        $total = $commentRepository->getTotalComment($figure);
        
        if (!$figure) {
            $this->addFlash('danger', "Cette figure n'existe pas");
            return $this->RedirectToRoute('main');
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
            'comments' => $comments,
            'total' =>$total,
            'limit'=>$limit,
            'page'=>$page,
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

            if (!$images) {
                $this->uploadImages->uploadDefault($figure);
            }

            $this->uploadImages->Upload($images, $figure);

            $figure->setWriter($user);
            $figure->setSlug(strtolower($slugger->slug($figure->getName())));
            $figure->setDate(new DateTime());
            $figure->setDateMod(new DateTime());
            $em->persist($figure);
            $em->flush();

            //reglage de l'image principale 
            $main = $form->get('main')->getData();
            $figure_id = $figure->getId();
            $this->mainImage->ChangeMainImage($figure_id, $main);

            $this->addFlash('success', "La figure a été créée");

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
            //throw $this->createNotFoundException("Cette figure n'existe pas");
            $this->addFlash('danger', "Cette figure n'existe pas");
            return $this->RedirectToRoute('main');
        }

        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        $user = $securit->getUser();
        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();
            if (!$images) {
                $this->uploadImages->uploadDefault($figure);
            }
            $this->uploadImages->Upload($images, $figure);

            $figure->setWriter($user);
            $figure->setSlug(strtolower($slugger->slug($figure->getName())));
            //$figure->setDate(new DateTime());
            $figure->setDateMod(new DateTime());

            $em->flush();
            //reglage de l'image principale 
            $main = $form->get('main')->getData();
            $figure_id = $figure->getId();
            $this->mainImage->ChangeMainImage($figure_id, $main);

            $this->addFlash('success', "La figure a été modifée");

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
        $this->addFlash('success', "La figure a été supprimée");
        return $this->RedirectToRoute('main');
    }
}
