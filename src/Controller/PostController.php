<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="postList")
     *
     * @return Response
     */
    public function list(): Response
    {
        $posts = [];

        return $this->render('post/list.html.twig', [
            'posts' => $posts,
        ]);
    }
}
