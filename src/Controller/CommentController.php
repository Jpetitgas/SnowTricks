<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Figure;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    protected $em;
    protected $securit;
    protected $commentRepository;
    

    public function __construct(EntityManagerInterface $em, Security $securit, CommentRepository $commentRepository)
    {
        $this->em = $em;
        $this->securit =$securit;
        $this->commentRepository=$commentRepository;
        
    }

    public function showComments($request, $figure, $page)
    {
        $comments = $this->commentRepository->getPaginationComments($figure, $page, 5);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $this->create($figure, $comment->getContent());
            return ;
        }
        $fromView = $form->createView();
        return array($comments,$fromView);
    }
    
    public function create(Figure $figure, $content)
    {
        $comment = new Comment();
        $user = $this->securit->getUser();
        $comment->setContent($content)
            ->setWriter($user)
            ->setFigure($figure);
        $this->em->persist($comment);
        $this->em->flush();
    }
}
