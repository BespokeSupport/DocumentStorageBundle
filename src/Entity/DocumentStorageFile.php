<?php

namespace BespokeSupport\DocumentStorageBundle\Entity;

use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityCreatedTrait;
use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityIsDeletedTrait;
use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityUpdatedTrait;
use BespokeSupport\DocumentStorageBundle\Base\DocumentStorageBaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class DocumentStorageFile
 * @package BespokeSupport\DocumentStorageBundle\Entity
 * @ORM\Table(
 *     "document_storage_file",
 *     indexes={
 *      @ORM\Index(name="is_deleted", columns={"is_deleted"}),
 *      @ORM\Index(name="file_mime_type", columns={"file_mime_type"}),
 *      @ORM\Index(name="file_name", columns={"file_name"})
 *     },
 *     uniqueConstraints={
 *      @ORM\UniqueConstraint(name="hash", columns={"hash"}),
 *     }
 * )
 * @ORM\Entity(repositoryClass="BespokeSupport\DocumentStorageBundle\Repository\DocumentStorageRepositoryFile")
 */
class DocumentStorageFile extends DocumentStorageBaseEntity
{
    use EntityCreatedTrait;
    use EntityUpdatedTrait;
    use EntityIsDeletedTrait;

    /**
     * @var DocumentStorageContent
     * @ORM\OneToOne(targetEntity="DocumentStorageContent", cascade={"all"})
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     */
    public $content;
    /**
     * @var DocumentStorageEntity[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="DocumentStorageEntity", inversedBy="files", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="document_storage_file_entities",
     *     joinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="entity_id", referencedColumnName="id")}
     * )
     **/
    public $entities;
    /**
     * @var \DateTime|null
     * @ORM\Column(name="file_created", type="datetime", nullable=true)
     */
    public $fileCreated;
    /**
     * @var string
     * @ORM\Column(name="file_extension", type="string", length=191, nullable=true)
     */
    public $fileExtension;
    /**
     * @var string
     * @ORM\Column(name="file_extension_original", type="string", length=191, nullable=true)
     */
    public $fileExtensionOriginal;
    /**
     * @var string
     * @ORM\Column(name="file_mime_type", type="string", length=191)
     */
    public $fileMime;
    /**
     * @var \DateTime|null
     * @ORM\Column(name="file_modified", type="datetime", nullable=true)
     */
    public $fileModified;
    /**
     * File size in bytes
     * @var int
     * @ORM\Column(name="file_size", type="integer")
     */
    public $fileSize;
    /**
     * @var string
     * @ORM\Column(name="file_name", type="string", length=191)
     */
    public $filename;
    /**
     * @var string
     * @ORM\Column(name="file_name_original", type="string", length=191, nullable=true)
     */
    public $filenameOriginal;
    /**
     * @var string
     * @ORM\Column(name="hash", type="string", length=191, nullable=false)
     */
    public $hash;
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    /**
     * If the files are not all stored in one directory then this helps split them up
     *
     * @var string
     * @ORM\Column(name="file_path", type="string", length=191, nullable=true)
     */
    public $path;
    /**
     * @ORM\OneToMany(targetEntity="DocumentStoragePermission", mappedBy="file")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true)
     **/
    public $permissions;
    /**
     * @var \SplFileInfo
     */
    public $splFileInfo;
    /**
     * @var DocumentStorageTag[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="DocumentStorageTag", inversedBy="files", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="document_storage_file_tags",
     *     joinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag", referencedColumnName="tag")}
     * )
     **/
    public $tags;

    /**
     * @param \SplFileInfo|UploadedFile $splFileInfo
     */
    public function __construct(\SplFileInfo $splFileInfo = null)
    {
        parent::__construct();

        $this->tags = new ArrayCollection();

        if ($splFileInfo) {
            $this->setFileInfo($splFileInfo);

            $this->setFileCreated(\DateTime::createFromFormat('U', $splFileInfo->getCTime()));
            $this->setFileModified(\DateTime::createFromFormat('U', $splFileInfo->getMTime()));

            $this->setFileSize($splFileInfo->getSize());

            if ($splFileInfo instanceof UploadedFile) {
                $this->setFilename($splFileInfo->getClientOriginalName());
                $this->setFilenameOriginal($splFileInfo->getClientOriginalName());
            } else {
                $this->setFilename($splFileInfo->getFilename());
                $this->setFilenameOriginal($splFileInfo->getFilename());
            }
        }
    }

    /**
     * @deprecated
     * @return DocumentStorageContent
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @deprecated
     * @param DocumentStorageContent $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @deprecated
     * @return \DateTime|null
     */
    public function getFileCreated()
    {
        return $this->fileCreated;
    }

    /**
     * @deprecated
     * @param \DateTime|null $fileCreated
     */
    public function setFileCreated($fileCreated)
    {
        $this->fileCreated = $fileCreated;
    }

    /**
     * @deprecated
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @deprecated
     * @param string $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }

    /**
     * @deprecated
     * @return string
     */
    public function getFileExtensionOriginal()
    {
        return $this->fileExtensionOriginal;
    }

    /**
     * @deprecated
     * @param string $fileExtensionOriginal
     */
    public function setFileExtensionOriginal($fileExtensionOriginal)
    {
        $this->fileExtensionOriginal = $fileExtensionOriginal;
    }

    /**
     * @deprecated
     * @return \SplFileInfo
     */
    public function getFileInfo()
    {
        return $this->splFileInfo;
    }

    /**
     * @deprecated
     * @return string
     */
    public function getFileMime()
    {
        return $this->fileMime;
    }

    /**
     * @deprecated
     * @param string $fileMime
     */
    public function setFileMime($fileMime)
    {
        $this->fileMime = $fileMime;
    }

    /**
     * @deprecated
     * @return \DateTime|null
     */
    public function getFileModified()
    {
        return $this->fileModified;
    }

    /**
     * @deprecated
     * @param \DateTime|null $fileModified
     */
    public function setFileModified($fileModified)
    {
        $this->fileModified = $fileModified;
    }

    /**
     * @deprecated
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @deprecated
     * @param int $fileSize
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @deprecated
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @deprecated
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @deprecated
     * @return string
     */
    public function getFilenameOriginal()
    {
        return $this->filenameOriginal;
    }

    /**
     * @deprecated
     * @param string $filenameOriginal
     */
    public function setFilenameOriginal($filenameOriginal)
    {
        $this->filenameOriginal = $filenameOriginal;
    }

    /**
     * @deprecated
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @deprecated
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @deprecated
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @deprecated
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @deprecated
     * @return \SplFileInfo
     */
    public function getSplFileInfo()
    {
        return $this->splFileInfo;
    }

    /**
     * @deprecated
     * @param \SplFileInfo $splFileInfo
     */
    public function setSplFileInfo($splFileInfo)
    {
        $this->splFileInfo = $splFileInfo;
    }

    /**
     * @deprecated
     * @param \SplFileInfo $splFileInfo
     * @return $this
     */
    public function setFileInfo(\SplFileInfo $splFileInfo)
    {
        $this->splFileInfo = $splFileInfo;
        return $this;
    }

    /**
     * @return string
     */
    public function getHashedFilename()
    {
        $filename = $this->getHash();

        if ($this->getFileExtension()) {
            $filename .= '.'. $this->getFileExtension();
        }

        return $filename;
    }
}
