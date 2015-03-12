<?php

namespace BespokeSupport\DocumentStorageBundle\Command;

//
use BespokeSupport\DocumentStorage\Entity\DocumentStorageFile;
use BespokeSupport\DocumentStorage\Entity\DocumentStorageTag;
use BespokeSupport\Mime\FileMimes;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
//


class DocumentStorageRemoveTagCommand extends ContainerAwareCommand
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
            ->setName('bespoke:document:tag:remove')
            ->addArgument('tag', InputArgument::REQUIRED);
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tagString = $input->getArgument('tag');

        /**
         * @var $repo \BespokeSupport\DocumentStorage\Repository\DocumentStorageRepository
         */
        $repo = $this->getContainer()->get('bespoke_support.document_storage.repository');

        $entities = $repo->entitiesByTag('hi');

    }
}
