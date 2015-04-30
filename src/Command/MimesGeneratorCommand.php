<?php

namespace BespokeSupport\DocumentStorageBundle\Command;

use BespokeSupport\Mime\FileMimesGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MimesGeneratorCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('bs:mimes:generate');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        FileMimesGenerator::composerGenerate();
    }
}
