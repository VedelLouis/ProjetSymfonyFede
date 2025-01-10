<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\CustomField;
use App\Form\ContactType;
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

}