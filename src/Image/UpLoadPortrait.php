<?php

namespace App\Image;

use App\Entity\Portrait;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UpLoadPortrait extends AbstractController
{
    public function manager($portrait, $user, $route)
    {
        if (!$portrait) {
            if ($route == 'create') {
                $this->upLoadDefault($user);
            }
        } else {
            $this->upLoad($portrait, $user);
        }
        
    }

    /**
     * @param mixed $portrait
     *
     * @return [type]
     */
    public function upLoad($portrait, User $user)
    {
        $fichier = md5(uniqid()).'.'.$portrait->guessExtension();
        $portrait->move(
            $this->getParameter('images_directory'),
            $fichier
        );
        $img = new Portrait();
        $img->setName($fichier);

        $user->setPortrait($img);
    }

    /**
     * @return [type]
     */
    public function upLoadDefault(User $user)
    {
        $url = $this->getParameter('images_default').'/'.'default_user.png';
        $file_name = md5(uniqid()).'.jpg';
        $file = $this->getParameter('images_directory').'/'.$file_name;
        copy($url, $file);
        $img = new Portrait();
        $img->setName($file_name);

        $user->setPortrait($img);
    }
}
