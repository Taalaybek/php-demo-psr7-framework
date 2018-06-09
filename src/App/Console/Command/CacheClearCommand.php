<?php

namespace App\Console\Command;

use App\Service\FileManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheClearCommand extends Command
{
    private $paths;
    private $files;

    public function __construct(array $paths, FileManager $files)
    {
        parent::__construct();
        $this->paths = $paths;
        $this->files = $files;
    }

    protected function configure(): void
    {
        $this
            ->setName('cache:clear')
            ->setDescription('Clear caches')
            ->addArgument('alias', InputArgument::OPTIONAL, 'The alias of available paths.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Flush file caches</comment>');

        if ($alias = $input->getArgument('alias')) {
            if (!array_key_exists($alias, $this->paths)) {
                throw new \InvalidArgumentException('Unknown path alias "' . $alias . '"');
            }
            $paths = [$alias => $this->paths[$alias]];
        } else {
            $paths = $this->paths;
        }

        foreach ($paths as $alias => $path) {
            if ($this->files->exists($path)) {
                $output->writeln('Remove ' . $path);
                $this->files->delete($path);
            } else {
                $output->writeln('Skip ' . $path);
            }
        }

        $output->writeln('<info>Done!</info>');
    }
}
