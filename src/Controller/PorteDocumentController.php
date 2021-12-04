<?php

namespace App\Controller;

use App\Configuration\FileLocations;
use App\Entity\PorteDocument;
use App\Form\PorteDocumentType;
use App\Utils\PathCanonicalize;
use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PorteDocumentRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/porte/document")
 */
class PorteDocumentController extends AbstractController
{

    public function __construct(EntityManagerInterface $em, Filesystem $filesystem, FileLocations $fileLocations)
   
    {
        $this->em = $em;
        $this->filesystem = $filesystem;
        $this->fileLocations = $fileLocations;
    }

    /**
     * @Route("/", name="porte_document_index", methods={"GET"})
     */
    public function index(PorteDocumentRepository $porteDocumentRepository): Response
    {
        return $this->render('porte_document/index.html.twig', [
            'porte_documents' => $porteDocumentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="porte_document_new", methods={"GET","POST"})
     */
    function new (Request $request, string $porteDocumentBasePath): Response {

        // dd($porteDocumentBasePath);
        $porteDocument = new PorteDocument();
        $form = $this->createForm(PorteDocumentType::class, $porteDocument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filesystem = new Filesystem();
            $data = $form->getData();
            // dd($data);
            if ($data->getNom() !== null && !empty(trim($data->getNom()))) {
                try {
                    $fullPath = $porteDocumentBasePath . '/' . $data->getNom();
                    $folder = $filesystem->mkdir($fullPath);
                     $this->addFlash('success', 'porte-document.create_folder_success');
                    //dd($folder);
                    $this->em->persist($porteDocument);
                    $this->em->flush();
                } catch (IOExceptionInterface $exception) {
                    echo "Une erreur s'est produite lors de la création de votre dossier à " . $exception->getPath();
                      $this->addFlash('danger', 'porte-document.create_folder_error');
                }
            }
            // $this->em->persist($porteDocument);
            // $this->em->flush();

            return $this->redirectToRoute('porte_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('porte_document/new.html.twig', [
            // 'porte_document' => $porteDocument,
            'form' => $form,
            //'folder' => $folder,
        ]);
    }

    /**
     * @Route("/{porteDocumentBasePath}", name="porte_document_show", methods={"GET"})
     */
    public function show(PorteDocument $porteDocument, string $porteDocumentBasePath): Response
    {
        //$location = $this->fileLocations->get($porteDocumentBasePath);

       // $finder = $this->findFiles($location->getBasepath(), $path);
       // $folders = $this->findFolders($location->getBasepath(), $path);

        //$parent = $path !== '/' ? Path::canonicalize($path . '/..') : '';
        return $this->render('porte_document/show.html.twig', [
            'porte_document' => $porteDocument,
            //'parent' => $parent,
            //  'path' => $path,
            //  'location' => $location,
            // 'finder' => $pager,
            // 'folders' => $folders,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="porte_document_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PorteDocument $porteDocument): Response
    {
        $form = $this->createForm(PorteDocumentType::class, $porteDocument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('porte_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('porte_document/edit.html.twig', [
            'porte_document' => $porteDocument,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="porte_document_delete", methods={"POST"})
     */
    public function delete(Request $request, PorteDocument $porteDocument): Response
    {
        if ($this->isCsrfTokenValid('delete' . $porteDocument->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($porteDocument);
            $entityManager->flush();
        }
        // $path = $this->getFromRequest('path');
        // $location = $this->getFromRequest('location');
        // $location = $this->fileLocations->get($location);

        // $folder = Path::canonicalize($location->getBasepath() . '/' . $path);

        // if (!$this->filesystem->exists($folder)) {
        //     $this->addFlash('warning', 'porte-document.delete_folder_missing');
        // } else {
        //     try {
        //         $this->filesystem->remove($folder);
        //         $this->addFlash('success', 'porte-document.delete_folder_successful');
        //     } catch (IOException $e) {
        //         $this->addFlash('danger', 'porte-document.delete_folder_error');
        //     }

        return $this->redirectToRoute('porte_document_index', [
            // 'location' => $this->getFromRequest('location'),
            //     'path' => Path::canonicalize($path . '/..'),

        ], Response::HTTP_SEE_OTHER);
    }

    private function findFolders(string $base, string $path): Finder
    {
        $fullpath = PathCanonicalize::canonicalize($base, $path);

        $finder = new Finder();
        $finder->in($fullpath)->depth('== 0')->directories()->sortByName();

        return $finder;
    }

    private function findFiles(string $base, string $path): Finder
    {
        $fullpath = PathCanonicalize::canonicalize($base, $path);

        $finder = new Finder();
        $finder->in($fullpath)->depth('== 0')->files()->sortByName();

        return $finder;
    }
}
