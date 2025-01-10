<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\CustomField;
use App\Entity\Group;
use App\Form\ContactType;
use App\Form\GroupType;
use App\Repository\ContactRepository;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;  // Importation du EntityManagerInterface
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class GroupController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/groups', name: 'group_list')]
    public function listGroups(GroupRepository $groupRepository): Response
    {
        $groups = $groupRepository->findAll();
        return $this->render('group/listGroup.html.twig', [
            'groups' => $groups,
        ]);
    }

    #[Route('/add_group', name: 'group_add')]
    public function addGroup(Request $request): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contacts = $form->get('contacts')->getData();
            foreach ($contacts as $contact) {
                $group->addContact($contact);

                if (!$contact->getGroups()->contains($group)) {
                    $contact->addGroup($group);
                }
                $this->entityManager->persist($contact);
            }

            $this->entityManager->persist($group);
            $this->entityManager->flush();

            return $this->redirectToRoute('group_list');
        }

        return $this->render('group/addGroup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/groups/{id}/edit', name: 'group_edit', methods: ['POST'])]
    public function editGroup(Request $request, Group $group): Response
    {
        $name = $request->request->get('name');

        if (!$name) {
            $this->addFlash('error', 'Le nom du groupe ne peut pas être vide.');
            return $this->redirectToRoute('group_list');
        }

        $group->setName($name);
        $this->entityManager->flush();

        $this->addFlash('success', 'Groupe modifié avec succès.');
        return $this->redirectToRoute('group_list');
    }

    #[Route('/group/delete/{id}', name: 'group_delete', methods: ['GET'])]
    public function deletegroup(int $id, GroupRepository $groupRepository, EntityManagerInterface $entityManager): Response
    {
        $group = $groupRepository->find($id);

        if (!$group) {
            $this->addFlash('error', 'Groupe non trouvé.');
            return $this->redirectToRoute('group_list');
        }

        $entityManager->remove($group);
        $entityManager->flush();

        $this->addFlash('success', 'Groupe supprimé avec succès.');

        return $this->redirectToRoute('group_list');
    }

}