<?php


namespace App\EventListener\DoctrineEventListener;


use App\Entity\Document;
use App\Utils\FileUtils;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class DocumentEventListener implements EventSubscriberInterface
{

    private string $porteDocumentBasePath;
    /**
     * @var Security
     */
    private Security $security;
    private string $trashBasePath;
    private bool $deleteData = false;

    /**
     * DocumentEventListener constructor.
     * @param string $porteDocumentBasePath
     * @param Security $security
     * @param string $trashBasePath
     */
    public function __construct(string  $porteDocumentBasePath, Security $security,string  $trashBasePath)
    {
        $this->porteDocumentBasePath = $porteDocumentBasePath;
        $this->security = $security;
        $this->trashBasePath = $trashBasePath;
    }

    public function getSubscribedEvents(): array
    {
            return [
                Events::postPersist,
                Events::postRemove,
                Events::postUpdate,
                Events::prePersist,
               // Events::preRemove,
                Events::preUpdate,

            ];
    }

    /**
     * Evenement permetant de suprimer le fichier lié a un docmument
     * @param LifecycleEventArgs $event
     * @throws \Exception
     */
    public  function preUpdate( LifecycleEventArgs $event): void
    {
        $document = $event->getObject();
        $manager = $event->getObjectManager()  ;
        if ($document instanceof Document && $document->getIsDelete())  {

          $this->deleteData = true;
        }
    }
    /**
     * Evenement permetant de suprimer le fichier lié a un docmument
     * @param LifecycleEventArgs $event
     * @throws \Exception
     */
   /* public  function postRemove( LifecycleEventArgs $event): void  {
        if ($event->getObject() instanceof Document) {
            try {
                $document = $event->getObject();
                 $objectManager = $event->getObjectManager();
                 rename($this->getFielPath($document->getPorteDocument()->getNom(),$document->getFilename()),$this->getTrashFilePath($document->getPorteDocument()->getNom(),$document->getFilename()));
                     } catch (\Exception $exception) {
                echo "An error occurred while creating your directory at ".$exception->getPath();

                       $objectManager->persist($document);
                       $objectManager->flush();
                        throw $exception;
            }

        }else {
            return;
        }
    }*/

    /**
     * Evenement permetant de spécifier le nom du fichier avant la persistance au niveau de la base de donnée {Numero de refrence} - {le nom originel du fichier}
     * @param LifecycleEventArgs $event
     */
    public  function prePersist(LifecycleEventArgs $event) :void {
        $document = $event->getObject();
        $manager = $event->getObjectManager()  ;
        if ($document instanceof Document ) {
                    $file =  $document->getFile();
                    $document->setSize($file->getSize());

            $document->setFileName($document->getRefnumero() . '-'. $file->getClientOriginalName());

            }

        }

    /**
     * Fonction permetant de dauvegarder le fichier dans le system après sa ssauvegarde dans la base de donnée
     * @param LifecycleEventArgs $event
     */
    public function postPersist( LifecycleEventArgs $event): void
    {
        $document = ( $event->getObject()) ;

        if ($document instanceof Document ) {
            /**
             * @var Document
             */
            $file =  $document->getFile();
            $file->move($this->getDirectory( $document->getPorteDocument()->getNom()),$document->getFilename());
    }
}

    /**
     * Fonction permetant de faire l'update du fichier
     * @param LifecycleEventArgs $event
     */
    public  function postUpdate(LifecycleEventArgs $event) :void {
    $document = $event->getObject();
    $manager = $event->getObjectManager();
    if ($document instanceof Document ) {
        $file =  $document->getFile();
        /**
         * Vérification de l'existance d'un nouveau fichier pour porceder a l'upload
         */
        if(($file)){
            //suppression de l'ancien fichier
            unlink($this->getFielPath($document->getPorteDocument()->getNom(), $document->getFilename()));
            $document->setFileName($document->getRefnumero() . '-'. $file->getClientOriginalName());
            //suppression de l'espace avant le numero de refernce
            $document->setRefnumero(trim($document->getRefnumero()));
            //Reset file size
            $document->setSize($file->getSize());

            //réinitialisation du champ de type Fichier
            $document->setFile(null);
            //Ajout du nouveau fichier
            $file->move($this->getDirectory( $document->getPorteDocument()->getNom()), $document->getFilename());
            //persisatnce des modification des champ dans la BD
            $manager->flush();
        } else if ($this->deleteData == true) {
            $this->deleteData = false;
          //  dd($this->getFielPath($document->getPorteDocument()->getNom(),$document->getFilename()),$this->getTrashFilePath($document->getPorteDocument()->getNom(),$document->getFilename()));
            try {
                rename($this->getFielPath($document->getPorteDocument()->getNom(),$document->getFilename()),$this->getTrashFilePath($document->getPorteDocument()->getNom(),$document->getFilename()));
            } catch (\Exception $exception) {
                echo "An error occurred while creating your directory at ".$exception->getPath();

                $manager->persist($document);
                $manager->flush();
                throw $exception;
            }
        }

    }
}
use FileUtils;
}

