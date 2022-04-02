<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Declaration du faker 
        $faker = Faker\Factory::create("fr_FR");

        for ($i = 0; $i <= mt_rand(6,8); $i++){
            //Creation categories
            $categorie = new Categorie();
            $categorie -> setLibelle($faker->word());

            $manager->persist($categorie);

            //Creation utilisateurs
            $utilisateur = new Utilisateur();
            $utilisateur -> setEmail($faker->safeEmail())
                         -> setNom($faker->firstName($gender = 'male'|'female'))
                         -> setPrenom($faker->lastName())
                         -> setUsername($faker->userName())
                         -> setPassword($faker->password());

            $manager->persist($utilisateur);

            for ($j = 0; $j <= mt_rand(2,4); $j++){
                //Creation Posts
                $post = new Post();
                $post -> setCategorie($categorie)
                      -> setUtilisateur($utilisateur)
                      -> setTitre($faker->sentence($nbWords = 3, $variableNbWords = true))
                      -> setDescription($faker->text($maxNbChars = 200))
                      -> setDatePublication($faker->dateTimeBetween($startDate = '-5 months', $endDate = 'now'))
                      -> setImage($faker->imageUrl($width = 640, $height = 480)); 

                $manager->persist($post);

                for ($k = 0; $k <= mt_rand(3,7); $k++){
                    //Creation Commentaires
                    $commentaire = new Commentaire();
                    $commentaire -> setPost($post)
                                 -> setContenu($faker->text($maxNbChars = 200))
                                 -> setDatePublication($faker->dateTimeInInterval($startDate = $post->getDatePublication(), $interval = '+ 10 days'));              
                    
                    $manager->persist($commentaire);
                }
            }
        }

        $manager->flush();
    }
}
