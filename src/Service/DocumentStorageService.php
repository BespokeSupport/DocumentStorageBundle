<?php

namespace BespokeSupport\DocumentStorageBundle\Service;

use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageFile;
use BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageText;
use BespokeSupport\Mime\FileMimes;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class DocumentStorageService
 * @package BespokeSupport\DocumentStorageBundle\Service
 */
class DocumentStorageService
{
    use ContainerAwareTrait;

    const CLASS_PERMISSION = 'BespokeSupport\DocumentStorageBundle\Entity\DocumentStoragePermission';
    const CLASS_CONTENT = 'BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageContent';
    const CLASS_ENTITY = 'BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageEntity';
    const CLASS_FILE = 'BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageFile';
    const CLASS_TEXT = 'BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageText';
    const CLASS_TAG = 'BespokeSupport\DocumentStorageBundle\Entity\DocumentStorageTag';

    protected $parameters = [];
    /**
     * @var DocumentStorageManager
     */
    private $documentStorageManager;

    /**
     * @param DocumentStorageManager $documentStorageManager
     */
    public function __construct(DocumentStorageManager $documentStorageManager)
    {
        $this->documentStorageManager = $documentStorageManager;
    }

    /**
     * @return DocumentStorageManager
     */
    public function getManager()
    {
        return $this->documentStorageManager;
    }

    /**
     * @param $parameter
     * @return null|string
     */
    public function getParameter($parameter)
    {
        if (!count($this->parameters)) {
            $parameters = $this->container->getParameter('document_storage');

            if (count($parameters)) {
                $this->parameters = $parameters;
            }
        }

        if (array_key_exists($parameter, $this->parameters)) {
            return $this->parameters[$parameter];
        }

        return null;
    }

    /**
     * @return string
     * @throws DocumentStorageException
     */
    public function getStoragePathBase()
    {
        $parameterPathBase = null;
        // parameters Paths

        $parameterPathBase = $this->getParameter('path_base');

        // if no path use cache_dir
        if (!$parameterPathBase) {
            $parameterPathBase = dirname($this->container->get('kernel')->getRootDir()).'/document_storage/';
        }

        return $parameterPathBase;
    }

    /**
     * @param null $fileHash
     * @param \DateTime $fileCreated
     * @return string
     */
    public function getStoragePath($fileHash = null, \DateTime $fileCreated = null)
    {
        $pathBase = $this->getStoragePathBase();

        /*
         * null|date|partial
         */
        $parameterPathExtra = $this->getParameter('directory_strategy');

        /*
         * Adds additional paths
         * date_partial_day|date_partial_month|date_partial_year|numeric
         */
        $parameterPathDepth = $this->getParameter('directory_depth');

        switch ($parameterPathExtra) {
            case 'date':
                // TODO
                break;

            case 'partial':
                // TODO

                break;

            default:
                return $pathBase;
        }


        $path = $pathBase.$parameterPathExtra;

        return $path;
    }

    /**
     * @param \SplFileInfo $fileInfo
     * @return string
     */
    public function hashFromSplFile(\SplFileInfo $fileInfo)
    {
        $strategy = $this->getParameter('strategy_file_hash');

        $path = $fileInfo->getRealPath();

        $fileHash = hash_file($strategy, $path);

        return $fileHash;
    }

    /**
     * @param \SplFileInfo $fileInfo
     * @return DocumentStorageFile|DocumentStorageText|null
     */
    public function createFromSplFile(\SplFileInfo $fileInfo)
    {
        $fileHash = $this->hashFromSplFile($fileInfo);

        $file = $this->documentStorageManager->getByHash($fileHash);

        // todo - what if DocumentStorageText

        if ($file) {
            return $file;
        }

        // the file
        $file = new DocumentStorageFile($fileInfo);
        $file->setHash($fileHash);

        // mime
        $fInfoClass = new \finfo(FILEINFO_MIME_TYPE | FILEINFO_PRESERVE_ATIME);
        $mime_type = $fInfoClass->buffer(file_get_contents($fileInfo->getRealPath()));
        $file->setFileMime($mime_type);

        return $file;
    }


    /**
     * @param DocumentStorageFile $file
     * @return bool
     * @throws DocumentStorageException
     */
    public function saveFileContents(DocumentStorageFile $file)
    {
        $result = $this->documentStorageManager->saveFileContents($file);
        return $result;
    }

    /**
     * @param DocumentStorageFile $file
     * @return DocumentStorageFile
     * @throws DocumentStorageException
     */
    public function saveFile(DocumentStorageFile $file)
    {
        $saveResult = $this->saveToFileSystem($file);
        if ($saveResult) {
            $this->documentStorageManager->saveFile($file);
        }

        return $file;
    }


    /**
     * @param DocumentStorageFile $file
     * @return bool
     * @throws DocumentStorageException
     */
    public function saveToFileSystem(DocumentStorageFile $file)
    {
        if (!$file->newFile) {
            return true;
        }

        $path = $this->getStoragePath($file->getHash());

        if (!is_writeable($path)) {
            throw new DocumentStorageException(DocumentStorageException::PATH_NOT_ACCESSIBLE);
        }

        $fileSystem = new Filesystem();

        $savePath = $path.$file->getHash();

        if (!$file->getFileExtension() && $file->getFileMime()) {
            $mimes = new FileMimes();
            $extension = $mimes->getExtensionFromMime($file->getFileMime());
            if ($extension) {
                $file->setFileExtension($extension);
                $file->setFileExtensionOriginal($extension);
            }
        }

        if ($file->getFileExtension()) {
            $savePath .= '.'.$file->getFileExtension();
        }

        $fileInfo = $file->getFileInfo();
        $from = $fileInfo->getRealPath();
        try {
            $fileSystem->copy($from, $savePath);
            return true;
        } catch (\Exception $e) {
            throw new DocumentStorageException($e->getMessage());
        }
    }
}
