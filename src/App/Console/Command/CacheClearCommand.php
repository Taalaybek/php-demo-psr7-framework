<?php

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheClearCommand extends Command
{
    private $paths;

    public function __construct(array $paths)
    {
        parent::__construct();
        $this->paths = $paths;
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
            if (file_exists($path)) {
                $output->writeln('Remove ' . $path);
                $this->delete($path);
            } else {
                $output->writeln('Skip ' . $path);
            }
        }

        $output->writeln('<info>Done!</info>');
    }

    private function delete(string $path): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException('Undefined path ' . $path);
        }

        if (is_dir($path)) {
            foreach (scandir($path, SCANDIR_SORT_ASCENDING) as $item) {
                if ($item === '.' || $item === '..') {
                    continue;
                }
                $this->delete($path . DIRECTORY_SEPARATOR . $item);
            }
            if (!rmdir($path)) {
                throw new \RuntimeException('Unable to delete directory ' . $path);
            }
        } else {
            if (!unlink($path)) {
                throw new \RuntimeException('Unable to delete file ' . $path);
            }
        }
    }
}
