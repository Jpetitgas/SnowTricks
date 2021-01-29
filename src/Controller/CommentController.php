<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Figure;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;
    }
    public function create(User $user,Figure $figure, $content)
    {
        $comment= new Comment;
        $comment->setDate(new DateTime())
            ->setContent($content)
            ->setWriter($user)
            ->setFigure($figure);
        $this->em->persist($comment);
        $this->em->flush();       
       
    }
}
