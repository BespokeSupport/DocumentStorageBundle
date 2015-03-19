<?php

namespace BespokeSupport\DocumentStorageBundle\Command;

use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageFile;
use BespokeSupport\DocumentStorageBundle\Service\DocumentStorageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DocumentStorageTagRemoveCommand extends Command
{
    /**
     * @var DocumentStorageManager
     */
    private $documentStorageManager;

    /**
     * @param DocumentStorageManager $documentStorageManager
     */
    public function __construct(
        DocumentStorageManager $documentStorageManager
    ) {
        parent::__construct();

        $this->documentStorageManager = $documentStorageManager;
    }

    protected function configure()
    {
        $this
            ->setName('bs:document:tag:remove')
            ->addArgument('hash', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hash = $input->getArgument('hash');

        $entity = $this->documentStorageManager->getByHash($hash);

        if (!$entity) {
            $output->writeln('<error>File not found</error>');
            return;
        }

        $tags = count($entity->getTags());
        $type = ($entity instanceof DocumentStorageFile)? 'file' : 'text';
        $output->writeln("<info>$hash ($type) has $tags tags</info>");

        $this->documentStorageManager->removeAllTags($entity);
    }
}
