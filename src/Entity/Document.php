<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * Many docummens have on (the same) folder
     * @ORM\ManyToOne(targetEntity=PorteDocument::class, inversedBy="documents")
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id")
     */
    private $porteDocument;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refnumero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sujet;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="documents")
     */
    private $author;

    private $file;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile()
	{
		return $this->file;
	}

	public function setFile($file = null)
	{
		$this->file = $file;

		// Replacing a file ? Check if we already have a file for this entity
		if (null !== $this->extension)
		{
			// Save file extension so we can remove it later
			$this->tempFilename = $this->extension;

			// Reset values
			$this->extension = null;
			$this->name = null;
		}
	}

    /**
	* @ORM\PrePersist()
	* @ORM\PreUpdate()
	*/
	public function preUpload()
	{
		// If no file is set, do nothing
		if (null === $this->file)
		{
			return;
		}

		// The file name is the entity's ID
		// We also need to store the file extension
		$this->extension = $this->file->guessExtension();

		// And we keep the original name
		$this->name = $this->file->getClientOriginalName();
	}
    /**
	* @ORM\PostPersist()
	* @ORM\PostUpdate()
	*/
	public function upload()
	{
		// If no file is set, do nothing
		if (null === $this->file)
		{
			return;
		}

		// A file is present, remove it
		if (null !== $this->tempFilename)
		{
			$oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
			if (file_exists($oldFile))
			{
				unlink($oldFile);
			}
		}

		// Move the file to the upload folder
		$this->file->move(
			$this->getUploadRootDir(),
			$this->id.'.'.$this->extension
		);
	}

	/**
	* @ORM\PreRemove()
	*/
	public function preRemoveUpload()
	{
		// Save the name of the file we would want to remove
		$this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->extension;
	}

	/**
	* @ORM\PostRemove()
	*/
	public function removeUpload()
	{
		// PostRemove => We no longer have the entity's ID => Use the name we saved
		if (file_exists($this->tempFilename))
		{
			// Remove file
			unlink($this->tempFilename);
		}
	}
    public function getUploadDir()
	{
		// Upload directory
		return 'uploads/documents/';
		// This means /web/uploads/documents/
	}

	protected function getUploadRootDir()
	{
		// On retourne le chemin relatif vers l'image pour notre code PHP
		// Image location (PHP)
		return __DIR__.'/../web/'.$this->getUploadDir();
	}

	public function getUrl()
	{
		return $this->id.'.'.$this->extension;
	}

    
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPorteDocument(): ?PorteDocument
    {
        return $this->porteDocument;
    }

    public function setPorteDocument(?PorteDocument $porteDocument = null): self
    {
        $this->porteDocument = $porteDocument;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getRefnumero(): ?string
    {
        return $this->refnumero;
    }

    public function setRefnumero(?string $refnumero): self
    {
        $this->refnumero = $refnumero;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(?string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
