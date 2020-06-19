<?php

declare(strict_types=1);

namespace App\Command;

use App\DTO\RbcPost;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\RbcParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadRbcPostsCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected static $defaultName = 'app:loadRbcPosts';

    /**
     * @var RbcParser
     */
    private RbcParser $parser;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    /**
     * @var int
     */
    private int $countNew = 0;

    /**
     * @var int
     */
    private int $countUpdated = 0;

    /**
     * @inheritDoc
     *
     * @param RbcParser $parser
     * @param EntityManagerInterface $entityManager
     * @param PostRepository $postRepository
     */
    public function __construct(
        string $name = null,
        RbcParser $parser,
        EntityManagerInterface $entityManager,
        PostRepository $postRepository
    ) {
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
        $this->parser = $parser;

        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $posts = $this->parser->getFeedPosts();
        } catch (\Exception $exception) {
            $output->writeln('Something went wrong: ' . $exception->getMessage());
            return Command::SUCCESS;
        }

        $this->insertRbcPosts($posts);

        $output->writeln('New posts: ' . $this->countNew);
        $output->writeln('Updated posts: ' . $this->countUpdated);

        return Command::SUCCESS;
    }

    /**
     * @param RbcPost[] $items
     */
    private function insertRbcPosts(array $items)
    {
        foreach ($items as $item) {
            $existedPost = $this->postRepository->findOneByRbcId($item->getRbcId());
            if ($existedPost === null) {
                $post = new Post();
                ++$this->countNew;
            } else {
                $post = $existedPost;
            }

            $post->setDateModify($item->getPostLink()->getDateModify()->getTimestamp());
            $post->setTitle($item->getTitle());
            $post->setText($item->getText());
            $post->setRbcId($item->getRbcId());
            if ($item->getImage() !== null) {
                $post->setImageSrc($item->getImage()->getUrl());
                $post->setImageDescription($item->getImage()->getDescription());
            }

            $this->entityManager->persist($post);
            $this->entityManager->getUnitOfWork()->computeChangeSets();

            if (
                $this->entityManager->getUnitOfWork()->isEntityScheduled($post)
                && $existedPost !== null
            ) {
                ++$this->countUpdated;
            }
        }

        $this->entityManager->flush();
    }
}
