<?php

namespace BespokeSupport\DocumentStorageBundle\Entity;

use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityCreatedTrait;
use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityIsDeletedTrait;
use BespokeSupport\CreatedUpdatedDeletedBundle\Traits\EntityUpdatedTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class DocumentStorageFile
 * @package BespokeSupport\DocumentStorage\Entity
 * @ORM\Table("document_storage_file", indexes={
 *      @ORM\Index(name="is_deleted", columns={"is_deleted"}),
 *      @ORM\Index(name="file_hash", columns={"file_hash"}),
 *      @ORM\Index(name="file_mime_type", columns={"file_mime_type"}),
 *      @ORM\Index(name="file_name", columns={"file_name"})
 * }))
 * @ORM\Entity(repositoryClass="BespokeSupport\DocumentStorage\Repository\DocumentStorageRepository")
 */
class DocumentStorageFile
{
    // Entities
    use EntityCreatedTrait;
    use EntityUpdatedTrait;
    use EntityIsDeletedTrait;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var DocumentStorageEntity[]
     * @ORM\ManyToMany(targetEntity="DocumentStorageEntity", inversedBy="files", cascade={"persist"})
     * @ORM\JoinTable(name="document_storage_file_entities",
     *      joinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="entity_id", referencedColumnName="id")}
     * )
     **/
    protected $entities;
    /**
     * @var \DateTime|null
     * @ORM\Column(name="file_created", type="datetime", nullable=true)
     */
    protected $fileCreated;
    /**
     * @var \DateTime|null
     * @ORM\Column(name="file_modified", type="datetime", nullable=true)
     */
    protected $fileModified;
    /**
     * @var string
     * @ORM\Column(name="file_extension", type="string", length=255)
     */
    protected $fileExtension;
    /**
     * @var string
     * @ORM\Column(name="file_extension_original", type="string", length=255)
     */
    protected $fileExtensionOriginal;
    /**
     * @var string
     * @ORM\Column(name="file_mime_type", type="string", length=255)
     */
    protected $mimeType;
    /**
     * @var string
     * @ORM\Column(name="file_name", type="string", length=255)
     */
    protected $filename;
    /**
     * @var string
     * @ORM\Column(name="file_name_original", type="string", length=255)
     */
    protected $filenameOriginal;
    /**
     * If the files are not all stored in one directory then this helps split them up
     *
     * @var string
     * @ORM\Column(name="file_path", type="string", length=255, nullable=true)
     */
    protected $path = '';
    /**
     * @var string
     * @ORM\Column(name="file_hash", type="string", length=255, nullable=false)
     */
    protected $file_hash;
    /**
     * File size in bytes
     * @var integer
     * @ORM\Column(name="file_size", type="integer")
     */
    protected $size;
    /**
     * @var object|null
     */
    protected $relatedEntity;
    /**
     * @var DocumentStorageTag[]
     * @ORM\ManyToMany(targetEntity="DocumentStorageTag", inversedBy="files", cascade={"all"})
     * @ORM\JoinTable(name="document_storage_file_tags",
     *      joinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag", referencedColumnName="tag")}
     * )
     **/
    protected $tags;

    /**
     * @var DocumentStorageContent
     * @ORM\OneToOne(targetEntity="DocumentStorageContent", cascade={"all"})
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     */
    protected $content;

    /**
     * @ORM\OneToMany(targetEntity="DocumentStoragePermission", mappedBy="file")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true)
     **/
    protected $permissions;

    /**
     * @var \SplFileInfo
     */
    protected $splFileInfo;

    protected $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    public function getFile()
    {return $this->file;}


        /**
     * @param \SplFileInfo $splFileInfo
     */
    public function __construct(\SplFileInfo $splFileInfo = null)
    {
        if ($splFileInfo) {
            if ($splFileInfo->isReadable()) {

                $this->setFileInfo($splFileInfo);

                $this->file_created = \DateTime::createFromFormat('U', $splFileInfo->getCTime());
                $this->file_modified = \DateTime::createFromFormat('U', $splFileInfo->getMTime());

                $this->file_extension = $splFileInfo->getExtension();
                $this->file_extension_original = $splFileInfo->getExtension();
                $this->file_size = $splFileInfo->getSize();
                $this->file_name_original = $splFileInfo->getFilename();
                $this->file_name = $splFileInfo->getFilename();
            }
        }
    }

    /**
     * @param DocumentStorageTag $documentStorageTag
     * @return $this
     */
    public function addTag(DocumentStorageTag $documentStorageTag = null)
    {
        if ($documentStorageTag) {
            $this->tags[] = $documentStorageTag;
        }

        return $this;
    }

    /**
     * @return \SplFileInfo
     */
    public function getFileInfo()
    {
        return $this->splFileInfo;
    }

    /**
     * @return object|null
     */
    public function getRelatedEntity()
    {
        return $this->relatedEntity;
    }

    /**
     * @param $entity
     * @return DocumentStorageFile
     */
    public function setRelatedEntity($entity)
    {
        $this->relatedEntity = $entity;
        return $this;
    }

    /**
     * @param \SplFileInfo $splFileInfo
     * @return $this
     */
    public function setFileInfo(\SplFileInfo $splFileInfo)
    {
        $this->splFileInfo = $splFileInfo;
        return $this;
    }

    /**
     * @return DocumentStorageEntity[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param DocumentStorageEntity[] $entities
     */
    public function setEntities($entities)
    {
        $this->entities = $entities;
    }

    /**
     * @return \DateTime|null
     */
    public function getFileCreated()
    {
        return $this->fileCreated;
    }

    /**
     * @param \DateTime|null $fileCreated
     */
    public function setFileCreated($fileCreated)
    {
        $this->fileCreated = $fileCreated;
    }

    /**
     * @return \DateTime|null
     */
    public function getFileModified()
    {
        return $this->fileModified;
    }

    /**
     * @param \DateTime|null $fileModified
     */
    public function setFileModified($fileModified)
    {
        $this->fileModified = $fileModified;
    }

    /**
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @param string $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }

    /**
     * @return string
     */
    public function getFileExtensionOriginal()
    {
        return $this->fileExtensionOriginal;
    }

    /**
     * @param string $fileExtensionOriginal
     */
    public function setFileExtensionOriginal($fileExtensionOriginal)
    {
        $this->fileExtensionOriginal = $fileExtensionOriginal;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getFilenameOriginal()
    {
        return $this->filenameOriginal;
    }

    /**
     * @param string $filenameOriginal
     */
    public function setFilenameOriginal($filenameOriginal)
    {
        $this->filenameOriginal = $filenameOriginal;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getFileHash()
    {
        return $this->file_hash;
    }

    /**
     * @param string $file_hash
     */
    public function setFileHash($file_hash)
    {
        $this->file_hash = $file_hash;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return DocumentStorageTag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param DocumentStorageTag[] $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return DocumentStorageContent
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param DocumentStorageContent $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param mixed $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @return \SplFileInfo
     */
    public function getSplFileInfo()
    {
        return $this->splFileInfo;
    }

    /**
     * @param \SplFileInfo $splFileInfo
     */
    public function setSplFileInfo($splFileInfo)
    {
        $this->splFileInfo = $splFileInfo;
    }
    
    
    
    
    
}
