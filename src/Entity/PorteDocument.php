<?php

namespace App\Entity;

use App\Repository\PorteDocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PorteDocumentRepository::class)
 */
class PorteDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    
    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="porteDocument",cascade={"persist"}, orphanRemoval=true)
     */
    private $documents;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $delete_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_delete;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->is_delete = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setPorteDocument($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getPorteDocument() === $this) {
                $document->setPorteDocument(null);
            }
        }

        return $this;
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

    public function getFileCount():int {
        return count ($this->documents);
    }

    public function  __toString(): String
    {
        return $this->nom;
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
        if($this->is_delete == true) {
           foreach ($this->documents as $document) {
               if (!$document->getIsDelete()) {
                   $document->setIsDelete($is_delete);
                   $document->setDeleteAt(new \DateTimeImmutable());
               }

           }
        }


        return $this;
    }
}
