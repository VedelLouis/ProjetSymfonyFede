<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ContactController extends AbstractController
{
    // Liste des contacts
    #[Route('/contacts', name: 'contact_list')]
    public function listContacts(ContactRepository $contactRepository): Response
    {
        // Récupère tous les contacts
        $contacts = $contactRepository->findAll();

        // Affiche la vue Twig avec les contacts
        return $this->render('contact/list.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    // Créer un contact
    #[Route('/contact/create', name: 'contact_create')]
    public function createContact(Request $request, EntityManagerInterface $em): Response
    {
        $contact = new Contact();

        // Création du formulaire de contact
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contact);
            $em->flush();

            // Redirige vers la liste des contacts
            return $this->redirectToRoute('contact_list');
        }

        // Affiche la vue Twig avec le formulaire
        return $this->render('contact/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Modifier un contact
    #[Route('/contact/{id}/edit', name: 'contact_edit')]
    public function editContact(Request $request, Contact $contact, EntityManagerInterface $em): Response
    {
        // Création du formulaire de contact
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            // Redirige vers la liste des contacts
            return $this->redirectToRoute('contact_list');
        }

        // Affiche la vue Twig avec le formulaire
        return $this->render('contact/edit.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact,
        ]);
    }

    // Supprimer un contact
    #[Route('/contact/{id}/delete', name: 'contact_delete')]
    public function deleteContact(Contact $contact, EntityManagerInterface $em): Response
    {
        // Supprime le contact
        $em->remove($contact);
        $em->flush();

        // Redirige vers la liste des contacts
        return $this->redirectToRoute('contact_list');
    }
}
