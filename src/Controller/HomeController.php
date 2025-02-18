<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    // Renvoie un assortiment de recipe, comment et une liste de toutes les category
    #[Route('/', name: 'app_home')]
    public function listUsers(RecipeRepository $recipeRepository, CommentRepository $commentRepository, CategoryRepository $categoryRepository): Response
    {
        // Recettes à la une (trié en fonction de la note [et du nombre de commentaires], définir un max(par défaut: 6))

        $recipes = $recipeRepository->findNewBestRecipes(6);

        // Derniers commentaires (définir un max(par défaut: 6))

        $comments = $commentRepository->findLastComments(6);

        // Catégories (pas de limite car peu de catégories)

        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'recipes' => $recipes,
            'comments' => $comments,
            'categories' => $categories,
        ]);
    }
}
