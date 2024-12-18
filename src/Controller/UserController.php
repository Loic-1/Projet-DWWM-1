<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/detail/{user}', name: 'detail_user')]
    public function detailUser(User $user, UserRepository $userRepository): Response
    {
        $followers = $user->getFollowers();
        $followees = $user->getFollowees();
        $users = $userRepository->findAll();

        return $this->render('user/detailUser.html.twig', [
            'user' => $user,
            'followers' => $followers,
            'followees' => $followees,
            'users' => $users
        ]);
    }

    #[Route('/follow/{follower}/{followee}', name: 'follow_user')]
    public function followUser(User $follower = null, User $followee = null, EntityManagerInterface $entityManager): Response
    {

        if ($follower && $followee && ($follower != $followee)) {

            $follower->addFollowee($followee);
            $followee->addFollower($follower);

            $entityManager->persist($follower);
            $entityManager->persist($followee);
            $entityManager->flush();

            return $this->redirectToRoute('detail_user', [
                'user' => $follower->getId()
            ]);
        } else {

            return $this->redirectToRoute('detail_user', [
                'user' => $follower->getId()
            ]);
        }
    }

    #[Route('/unfollow/{follower}/{followee}', name: 'unfollow_user')]
    public function unfollowUser(User $follower = null, User $followee = null, EntityManagerInterface $entityManager): Response
    {

        if ($follower && $followee && ($follower != $followee)) {

            $follower->removeFollowee($followee);
            $followee->removeFollower($follower);

            $entityManager->persist($follower);
            $entityManager->persist($followee);
            $entityManager->flush();

            return $this->redirectToRoute('detail_user', [
                'user' => $follower->getId()
            ]);
        } else {

            return $this->redirectToRoute('detail_user', [
                'user' => $follower->getId()
            ]);
        }
    }
}
