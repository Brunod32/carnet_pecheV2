<?php

namespace App\Controller;

use App\Entity\Material;
use App\Form\MaterialType;
use App\Repository\MaterialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem;


#[Route('/material')]
class MaterialController extends AbstractController
{
    #[Route('/', name: 'app_material_index', methods: ['GET'])]
    public function index(MaterialRepository $materialRepository): Response
    {
        return $this->render('material/index.html.twig', [
            'materials' => $materialRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_material_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MaterialRepository $materialRepository, SluggerInterface $slugger): Response
    {
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();
            

            // this condition is needed because the 'photo' field is not required
            // so the file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                
                // Move the file to the directory where photos are stored
                try {
                    $photo->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', $e->getMessage());
                }
 
                // updates the 'photoname' property to store the PDF file name
                $material->setPhoto($newFilename);
            }
            $materialRepository->add($material, true);

            return $this->redirectToRoute('app_material_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('material/new.html.twig', [
            'material' => $material,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_material_show', methods: ['GET'])]
    public function show(Material $material): Response
    {
        return $this->render('material/show.html.twig', [
            'material' => $material,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_material_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Material $material, MaterialRepository $materialRepository): Response
    {
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();
            
            // this condition is needed because the 'photo' field is not required
            // so the file must be processed only when a file is uploaded
            if ($photo) {
                $newFilename = $material->getPhoto();
                
                // Move the file to the directory where photos are stored
                try {
                    $photo->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', $e->getMessage());
                }
 
                // updates the 'photoname' property to store the PDF file name
                $material->setPhoto($newFilename);
            }
            $materialRepository->add($material, true);

            return $this->redirectToRoute('app_material_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('material/edit.html.twig', [
            'material' => $material,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_material_delete', methods: ['POST'])]
    public function delete(Request $request, Material $material, MaterialRepository $materialRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$material->getId(), $request->request->get('_token'))) {
            $filename = $material->getPhoto();
            $materialRepository->remove($material, true);

            // Supprimer la photo du dossier upload du serveur
            $fs = new Filesystem();
            $fs->remove($this->getParameter('photos_directory').'/'.$filename);
        }

        return $this->redirectToRoute('app_material_index', [], Response::HTTP_SEE_OTHER);
    }
}
