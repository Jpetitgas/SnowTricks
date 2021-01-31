<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(User $user, Figure $figure, $content)
    {
        $comment = new Comment();
        $comment->setDate(new DateTime())
            ->setContent($content)
            ->setWriter($user)
            ->setFigure($figure);
        $this->em->persist($comment);
        $this->em->flush();
    }
}
