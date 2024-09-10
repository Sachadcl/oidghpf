<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Campus;
use App\Entity\User;
use App\Entity\City;
use App\Entity\Outing;
use App\Service\OutingService;
use Faker\Factory;
use Faker\Generator;
use App\Utils\Roles;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    protected Generator $faker;

    private OutingService $outingService;
    private Campus $campus;
    private User $user;
    private City $city;

    public static $CAMPUS_LIST = [
        'ENI Rennes',
        'ENI Nantes',
        'ENI Brest',
        'ENI Vannes',
        'ENI Quimper',
    ];


    function __construct(UserPasswordHasherInterface $passwordHasher, OutingService $outingService)
    {
        $this->faker = Factory::create('fr_FR');
        $this->passwordHasher = $passwordHasher;
        $this->outingService = $outingService;
    }



    private function initializeObjects(ObjectManager $manager): void
    {

        $this->campus = new Campus();
        $this->user = new User();
        $this->city = new City();



        $this->campus->setCampusName('ENI Rennes');

        $this->user->setFirstName($this->faker->firstName());
        $this->user->setLastName($this->faker->lastName());
        $this->user->setEmail("aaa@12aaa.com");
        $this->user->setRoles([Roles::USER->value]);
        $this->user->setUsername($this->faker->userName());
        $this->user->setTelephone($this->faker->phoneNumber());
        $this->user->setIdCampus($this->campus);
        $this->user->setProfilePicture($this->faker->imageUrl());
        $this->user->setPassword($this->passwordHasher->hashPassword($this->user, "aaa"));
        $this->user->setIsActive(true);


        $this->city->setcityName($this->faker->city());
        $this->city->setPlaceName($this->faker->company());
        $this->city->setStreetName($this->faker->streetName());
        $this->city->setZipcode($this->faker->postcode());

        $manager->persist($this->campus);
        $manager->persist($this->user);
        $manager->persist($this->city);
        $manager->flush();
    }

    public function load(ObjectManager $manager): void
    {

        $this->initializeObjects($manager);


        foreach (self::$CAMPUS_LIST as $campusName) {
            $this->createCampus($manager, $campusName);
        }

        for ($i = 0; $i < 10; $i++) {
            $this->createOutings($manager);
            $this->createUsers($manager);
            $this->createCities($manager);
        }

        $manager->flush();
    }


    public function createCampus(ObjectManager $manager, $campusName): void
    {
        $campus = new Campus();
        $campus->setCampusName($campusName);
        $manager->persist($campus);
        $manager->flush();
    }

    public function createCities(ObjectManager $manager): void
    {
        $city = new City();
        $city->setCityName($this->faker->city());
        $city->setPlaceName($this->faker->company());
        $city->setStreetName($this->faker->streetName());
        $city->setZipcode($this->faker->postcode());
        $manager->persist($city);
        $manager->flush();
    }

    public function createOutings(ObjectManager $manager): void
    {
        $outing = new Outing();
        $signedUser = $this->user;
        $outing->setIdCampus($this->campus);
        $outing->setOutingDate($this->faker->dateTimeBetween('2024-01-01', '2024-12-31'));
        $outing->setRegistrationDeadline($this->faker->dateTimeBetween('2023-01-01', '2024-12-31'));
        $outing->setState($this->outingService->calculateOutingState($outing));
        $outing->setIdCity($this->city);
        $outing->setOutingName($this->faker->company());

        $outing->setDescription($this->faker->text());
        $outing->setIdOrganizer($signedUser);
        $outing->setSlots($this->faker->numberBetween(1, 50));
        $outing->addIdMember($signedUser);
        $manager->persist($outing);
        $manager->flush();
    }



    public function createUsers(ObjectManager $manager): void
    {


        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($this->user, $this->faker->password());

        $user->setFirstName($this->faker->firstName());
        $user->setLastName($this->faker->lastName());
        $user->setEmail($this->faker->email());
        $user->setUsername($this->faker->userName());
        $user->setTelephone($this->faker->phoneNumber());
        $user->setRoles(["ROLE_USER"]);
        $user->setIdCampus($this->campus);
        $user->setProfilePicture($this->faker->imageUrl());

        $user->setPassword($hashedPassword);
        $user->setIsActive(true);

        $manager->persist($user);
    }
}
