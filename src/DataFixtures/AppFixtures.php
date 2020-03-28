<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Option;
use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        // Création d'un admin

        $adminUser = new User();
        $adminUser
                ->setGender(0)    
                ->setFirstName('Julien')
                ->setLastName('Bastian')
                ->setEmail('julien@bastian.paris')
                ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                ->setRoles(array('ROLE_ADMIN'));
                
        $manager->persist($adminUser);

        
        //Nous créons les gestionnaires

        $managerUsers = [];
        $genders = [0, 1];

        for($i = 1; $i <= 5; $i++) {
            $managerUser = new User();

            $gender = $faker->randomElement($genders);

            if($gender == 0) $civilite = 'Male';
            else $civilite = 'Female';

            $hash = $this->encoder->encodePassword($managerUser, 'password');

            $managerUser
                ->setGender($gender)
                ->setFirstName($faker->firstName($civilite))
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setHash($hash)
                ->setRoles(array('ROLE_PROPERTYMANAGER'));
                
            $manager->persist($managerUser);
            $managerUsers[] = $managerUser;

        }

        //Nous créons des options

        $option1 = new Option();
        $option1
            ->setName('Ascenseur');
        $manager->persist($option1);

        $option2 = new Option();
        $option2
            ->setName('Balcon');
        $manager->persist($option2);

        $option3 = new Option();
        $option3
            ->setName('Vue Mer');
        $manager->persist($option3);

        $option4 = new Option();
        $option4
            ->setName('Accès PMR');
        $manager->persist($option4);
            

        //Nous créons les propriétés

        for($i = 0; $i < 250; $i++) {
            $property = new Property();

            $user = $managerUsers[mt_rand(0, count($managerUsers) - 1)];

            $property
                ->setTitle($faker->words(3, true))
                ->setDescription($faker->sentences(3, true))
                ->setSurface($faker->numberBetween(20, 250))
                ->setRooms($faker->numberBetween(1, 10))
                ->setBedrooms($faker->numberBetween(1, 4))
                ->setFloor($faker->numberBetween(0, 8))
                ->setPrice($faker->numberBetween(95000, 4560000))
                ->setHeat($faker->numberBetween(0, count(Property::HEAT)-1))
                ->setCity($faker->city)
                ->setAddress($faker->streetAddress)
                ->setPostalCode($faker->numberBetween(10101, 98560))
                ->setSold(false)
                ->setLat($faker->latitude($min = -90, $max = 90))
                ->setLng($faker->longitude($min = -180, $max = 180))
                ->setAuthor($user)
                ->setManager($user); 
                
            $manager->persist($property);   
          }

        $manager->flush();
    }
}
