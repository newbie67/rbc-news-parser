<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Helper\TextTrimmer;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Urodoz\Truncate\TruncateInterface;
use Urodoz\Truncate\TruncateService;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="postList")
     *
     * @param PostRepository $postRepository
     * @param TextTrimmer $textTrimmer
     * @return Response
     */
    public function list(
        PostRepository $postRepository,
        TextTrimmer $textTrimmer
    ): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('post/list.html.twig', [
            'posts' => $posts,
            'textTrimmer' => $textTrimmer,
            'previewLength' => Post::PREVIEW_SIZE,
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
