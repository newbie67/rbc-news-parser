<?php

declare(strict_types=1);

namespace App\Command;

use App\DTO\RbcPost;
use App\Service\RbcParser;
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
     * @inheritDoc
     *
     * @param RbcParser $parser
     */
    public function __construct(string $name = null, RbcParser $parser)
    {
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
            echo 'Something went wrong: ' . $exception->getMessage();
            return Command::SUCCESS;
        }

        $this->insertRbcPosts($posts);

        return Command::SUCCESS;
    }


    private function insertRbcPosts(array $posts)
    {
        dump($posts);
//        foreach ($posts as $post) {
//
//        }

    }
}
