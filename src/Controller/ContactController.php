<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;  // Importation du EntityManagerInterface
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ContactController extends AbstractController
{
    private $entityManager;

    // Injection du EntityManagerInterface dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'contact_list')]
    public function listContacts(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findAll();
        return $this->render('contact/list.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/add_contact', name: 'contact_add')]
    public function addContact(Request $request, SluggerInterface $slugger): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('contact_images_directory'),
                        $newFilename
                    );
                    $contact->setImage($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
            }

            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            return $this->redirectToRoute('contact_list');
        }

        return $this->render('contact/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/contact/edit/{id}', name: 'contact_edit')]
    public function editContact(int $id, Request $request, SluggerInterface $slugger, ContactRepository $contactRepository): Response
    {
        $contact = $contactRepository->find($id);

        if (!$contact) {
            $this->addFlash('error', 'Contact non trouvé.');
            return $this->redirectToRoute('contact_list');
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('contact_images_directory'),
                        $newFilename
                    );

                    $oldImagePath = $this->getParameter('kernel.project_dir') . '/public/ContactImages/' . $contact->getImage();
                    if ($contact->getImage() && file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }

                    $contact->setImage($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Contact mis à jour avec succès.');
            return $this->redirectToRoute('contact_list');
        }

        return $this->render('contact/edit.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact,
        ]);
    }

    #[Route('/contact/delete/{id}', name: 'contact_delete', methods: ['GET'])]
    public function deleteContact(int $id, ContactRepository $contactRepository, EntityManagerInterface $entityManager): Response
    {
        $contact = $contactRepository->find($id);

        if (!$contact) {
            $this->addFlash('error', 'Contact non trouvé.');
            return $this->redirectToRoute('contact_list');
        }

        $imagePath = $this->getParameter('kernel.project_dir') . '/public/ContactImages/' . $contact->getImage();
        if ($contact->getImage() && file_exists($imagePath)) {
            unlink($imagePath);
        }

        $entityManager->remove($contact);
        $entityManager->flush();

        $this->addFlash('success', 'Contact supprimé avec succès.');

        return $this->redirectToRoute('contact_list');
    }

}