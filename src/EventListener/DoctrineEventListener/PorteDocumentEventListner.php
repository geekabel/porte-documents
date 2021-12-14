<?php


namespace App\EventListener\DoctrineEventListener;


use App\Entity\Document;
use App\Entity\PorteDocument;
use App\Repository\PorteDocumentRepository;
use App\Utils\FileUtils;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Security\Core\Security;

class PorteDocumentEventListner implements EventSubscriberInterface
{

    private string $porteDocumentBasePath;
    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var PorteDocumentRepository
     */
    private PorteDocumentRepository $porteDocumentRepository;

    private bool $delte = false;

    /**
     * @var String
     */
    private String $oldName;
    /**
     * DocumentEventListener constructor.
     * @param string $porteDocumentBasePath
     * @param Security $security
     * @param PorteDocumentRepository $porteDocumentRepository
     */
    public function __construct(string  $porteDocumentBasePath, Security $security,PorteDocumentRepository $porteDocumentRepository)
    {
        $this->porteDocumentBasePath = $porteDocumentBasePath;
        $this->security = $security;
        $this->porteDocumentRepository = $porteDocumentRepository;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postRemove,
            Events::preUpdate,
            Events::postLoad,
        ];
    }


    /**
     * Fonction permetant de garder en memoir le nom de l'ancien pour un dossier
     * @param LifecycleEventArgs $event
     */
    public  function postLoad( LifecycleEventArgs $event): void  {
        $porteDocument = $event->getObject();
        if ($event->getObject() instanceof PorteDocument) {
            $this->oldName = $porteDocument->getNom();
        }

    }


    /*public  function postUpDate( LifecycleEventArgs $event): void  {
        $porteDocument = $event->getObject();
        if ($porteDocument instanceof PorteDocument) {
           if($this->delte) {
               rmdir($this->getDirectory($porteDocument->getNom()));
               $this->delte = false;
           }
        }

    }*/

    /**
     * Suppression du porte document et des document
     * @param LifecycleEventArgs $event
     * @throws \Exception
     */
    public  function postRemove( LifecycleEventArgs $event): void  {
        $porteDocument = $event->getObject();
        if ($event->getObject() instanceof PorteDocument) {
            try {
                $this->removeDir($this->getDirectory($porteDocument->getNom()));
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }


    /**
     * Mise à jour du porte document
     * @param LifecycleEventArgs $event
     */
    public  function preUpdate(LifecycleEventArgs $event) :void {
        /**
         * @var PorteDocument
         */
        $porteDocument = $event->getObject();
        if ($porteDocument instanceof PorteDocument) {
           // dd($this->oldName , $porteDocument->getNom(), $this->oldName !== $porteDocument->getNom());
            if ($porteDocument->getIsDelete()) {
                $this->delte =  true;
            }
            if($this->oldName !== $porteDocument->getNom()){
                rename( $this->getDirectory($this->oldName),$this->getDirectory( $porteDocument->getNom()));
            }

        }
    }


    private function removeDir($dirname) {
        if (is_dir($dirname)) {
            $dir = new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS);
            foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST) as $object) {
                if ($object->isFile()) {
                    unlink($object);
                } elseif($object->isDir()) {
                    rmdir($object);
                } else {
                    throw new \Exception('Unknown object type: '. $object->getFileName());
                }
            }
            rmdir($dirname); // Now remove myfolder
        } else {
            throw new \Exception('This is not a directory');
        }
    }

    use FileUtils;
}

