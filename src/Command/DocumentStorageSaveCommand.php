<?php

namespace BespokeSupport\DocumentStorageBundle\Command;

use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageFile;
use BespokeSupport\DocumentStorageBundle\Service\DocumentStorageManager;
use BespokeSupport\DocumentStorageBundle\Service\DocumentStorageService;
use BespokeSupport\Mime\FileMimes;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DocumentStorageSaveCommand extends Command
{
    /**
     * @var DocumentStorageService
     */
    private $documentStorageService;
    /**
     * @var DocumentStorageManager
     */
    private $documentStorageManager;
    /**
     * @param DocumentStorageService $documentStorageService
     * @param DocumentStorageManager $documentStorageManager
     */
    public function __construct(
        DocumentStorageService $documentStorageService,
        DocumentStorageManager $documentStorageManager
    ) {
        $this->documentStorageService = $documentStorageService;
        $this->documentStorageManager = $documentStorageManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('bs:document:save')
            ->setDescription('Add file from local filesystem + Save')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to local file')
            ->addArgument('storage', InputArgument::OPTIONAL, 'DATABASE or FILE');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('file');
        $storage = strtolower($input->getArgument('storage'));

        $splFile = new \SplFileInfo($filePath);

        // check file exists
        $hash = $this->documentStorageService->hashFromSplFile($splFile);
        $file = $this->documentStorageManager->getByHash($hash);
        if ($file) {
            $output->writeln("<error>$hash exists</error>");
            return;
        }

        $file = $this->documentStorageService->createFromSplFile($splFile);

        // save to filesystem
        $this->documentStorageService->saveToFileSystem($file);

        // save to db
        $this->documentStorageManager->saveFile($file);
    }
}
