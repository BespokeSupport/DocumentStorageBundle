<?php

namespace BespokeSupport\DocumentStorageBundle\Command;

use BespokeSupport\DocumentStorage\Entity\DocumentStorageFile;
use BespokeSupport\DocumentStorage\Entity\DocumentStorageTag;
use BespokeSupport\Mime\FileMimes;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DocumentStorageAddCommand extends ContainerAwareCommand
{
    public function __construct(

    ) {
        parent::__construct();
    }


    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('bespoke:document:add')
            ->setDescription('Add file from local filesystem')
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

        $file = new DocumentStorageFile($splFile);

        $file->file_hash = hash_file('md5', $splFile->getRealPath());

        $fInfoClass = new \finfo(FILEINFO_MIME_TYPE | FILEINFO_PRESERVE_ATIME);
        $mime_type = $fInfoClass->buffer(file_get_contents($splFile->getRealPath()));
        $file->file_mime_type = $mime_type;

        $file->file_extension = (new FileMimes())->getExtensionFromMime($mime_type) ? : $file->file_extension_original;

        if ($file->file_extension != $file->file_extension_original) {
            $file->file_name = str_replace($file->file_extension_original, '', $splFile->getBasename()).$file->file_extension;
        }

        /**
         * @var $manager \BespokeSupport\DocumentStorage\Service\DocumentStorageManager
         */
        $manager = $this->getContainer()->get('bespoke_support.document_storage.entity_manager');

//        $file->addTag($manager->getOrCreateTag('hi'));

        switch ($storage) {
            case 'contents':
                $manager->saveFileContents($file);
                break;
            case 'file':
            default:
            $manager->saveFile($file);
                break;
        }
    }
}
