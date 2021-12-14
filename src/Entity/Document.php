<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use App\Utils\PathCanonicalize;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

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
    private \DateTimeImmutable $createdAt;

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
    public ?string $extension = NULL;
    public ?string $tempFilename = NULL;
    public ?string $name = NULL;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $delete_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_delete;

    public  function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->is_delete = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile()
	{
		return $this->file;
	}

    /**
     * @param null $file
     * @ORM\PostUpdate
     * @ORM\PostUpdate()
     */
	public function setFile($file = null)
                           	{
                           		$this->file = $file;
                           
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
public function getExtension(): String {
    $var = explode( '.' ,$this->getFilename());
    if(count($var)> 0) {
        return end($var);
    }
    return  '';
}

public function getSize(): ?int
{
    return $this->size;
}

public function setSize(int $size): self
{
    $this->size = $size;

    return $this;
}

public function getDeleteAt(): ?\DateTimeImmutable
{
    return $this->delete_at;
}

public function setDeleteAt(?\DateTimeImmutable $delete_at): self
{
    $this->delete_at = $delete_at;

    return $this;
}

public function getIsDelete(): ?bool
{
    return $this->is_delete;
}

public function setIsDelete(bool $is_delete): self
{
    $this->is_delete = $is_delete;

    return $this;
}


    public function getSizeFormat(): ?string
    {
        $base = log($this->size) / log(1024);
        $suffix = array("B", "KB", "MB", "GB", "TB");
        $f_base = floor($base);
        return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
    }


}
