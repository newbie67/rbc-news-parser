<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="postList")
     *
     * @param PostRepository $postRepository
     * @return Response
     */
    public function list(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('post/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/{rbcId}", name="onePost")
     *
     * @param string $rbcId
     * @param PostRepository $postRepository
     *
     * @return Response
     */
    public function onePost(string $rbcId, PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneByRbcId($rbcId);
        if ($post === null) {
            throw new NotFoundHttpException();
        }

        return $this->render('post/one.html.twig', [
            'post' => $post,
        ]);
    }
}
