<?php

namespace BespokeSupport\DocumentStorageBundle\Controller;

use BespokeSupport\DocumentStorageBundle\Service\DocumentStorageService;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as A;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class DownloadController
 * @package BespokeSupport\DocumentStorageBundle\Controller
 * @A\Route("/document", service="bs.document_storage.controller.download")
 */
class DownloadController extends Controller
{
    /**
     * @var DocumentStorageService
     */
    private $documentStorageService;
    /**
     * @param DocumentStorageService $documentStorageService
     */
    function __construct(DocumentStorageService $documentStorageService)
    {
        $this->documentStorageService = $documentStorageService;
    }

    /**
     * @A\Route("/download/{hash}", name="document_download")
     * @A\Route("/{hash}/download", name="document_download_bc")
     * @param $hash
     * @return BinaryFileResponse
     * @throws EntityNotFoundException
     */
    public function downloadFileAction($hash, Request $request)
    {
        $entity = $this->documentStorageService->getManager()->getByHash($hash);

        if (!$entity) {
            throw new EntityNotFoundException();
        }

        $directory = $this->documentStorageService->getStoragePath($entity->getHash());
        $path = $directory.$entity->getHashedFilename();
        $filename = $entity->getFilename();

        $filename .= '.' . $entity->getFileExtension();

        $response = new BinaryFileResponse($path);

        $response->trustXSendfileTypeHeader();

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );

        $response = $response->prepare($request);

        return $response;
    }
}
