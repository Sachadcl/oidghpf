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
        $this->bbb = new Campus();
        $this->user = new User();
        $this->city = new City();
        $this->aaa = new User();



        $this->campus->setCampusName('ENI Rennes');
        $this->bbb->setCampusName('ENI Nantes');


        $this->user->setFirstName($this->faker->firstName());
        $this->user->setLastName($this->faker->lastName());
        $this->user->setEmail("aaa@aaa.com");
        $this->user->setRoles([Roles::USER->value]);
        $this->user->setUsername($this->faker->userName());
        $this->user->setTelephone($this->faker->phoneNumber());
        $this->user->setIdCampus($this->campus);
        $this->user->setProfilePicture($this->faker->imageUrl());
        $this->user->setPassword($this->passwordHasher->hashPassword($this->user, "aaa"));
        $this->user->setIsActive(true);


        $this->aaa->setFirstName($this->faker->firstName());
        $this->aaa->setLastName($this->faker->lastName());
        $this->aaa->setEmail("bbb@bbb.com");
        $this->aaa->setRoles([Roles::ADMIN->value]);
        $this->aaa->setUsername($this->faker->userName());
        $this->aaa->setTelephone($this->faker->phoneNumber());
        $this->aaa->setIdCampus($this->bbb);
        $this->aaa->setProfilePicture($this->faker->imageUrl());
        $this->aaa->setPassword($this->passwordHasher->hashPassword($this->user, "aaa"));
        $this->aaa->setIsActive(true);

        $this->city->setcityName($this->faker->city());
        $this->city->setPlaceName($this->faker->company());
        $this->city->setStreetName($this->faker->streetName());
        $this->city->setZipcode($this->faker->postcode());

        $manager->persist($this->campus);
        $manager->persist($this->bbb);
        $manager->persist($this->user);
        $manager->persist($this->aaa);
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
        $outing->setOutingDate($this->faker->dateTimeBetween('2024-09-12', '2024-12-31'));
        $outing->setRegistrationDeadline($this->faker->dateTimeBetween('2024-09-11', '2024-12-31'));
        $outing->setState($this->outingService->calculateOutingState($outing));
        $outing->setIdCity($this->city);
        $outing->setOutingName($this->faker->company());

        $outing->setDescription($this->faker->text());
        $outing->setIdOrganizer($signedUser);
        $outing->setSlots($this->faker->numberBetween(1, 50));
        $outing->addIdMember($signedUser);
        $manager->persist($outing);
        $manager->flush();

        $outing2 = new Outing();
        $signedUser2 = $this->aaa;
        $outing2->setIdCampus($this->campus);
        $outing2->setOutingDate($this->faker->dateTimeBetween('2024-06-20', '2024-06-21'));
        $outing2->setRegistrationDeadline($this->faker->dateTimeBetween('2024-07-19', '2024-07-20'));
        $outing2->setState($this->outingService->calculateOutingState($outing2));
        $outing2->setIdCity($this->city);
        $outing2->setOutingName($this->faker->company());

        $outing2->setDescription($this->faker->text());
        $outing2->setIdOrganizer($signedUser2);
        $outing2->setSlots($this->faker->numberBetween(1, 50));
        $outing2->addIdMember($signedUser2);
        $manager->persist($outing2);
        $manager->flush();

        $outing3 = new Outing();
        $signedUser3 = $this->aaa;
        $outing3->setIdCampus($this->bbb);
        $outing3->setOutingDate($this->faker->dateTimeBetween('2024-12-30', '2024-12-31'));
        $outing3->setRegistrationDeadline($this->faker->dateTimeBetween('2023-12-30', '2023-12-31'));
        $outing3->setState($this->outingService->calculateOutingState($outing3));
        $outing3->setIdCity($this->city);
        $outing3->setOutingName($this->faker->company());

        $outing3->setDescription($this->faker->text());
        $outing3->setIdOrganizer($signedUser3);
        $outing3->setSlots($this->faker->numberBetween(1, 50));
        $outing3->addIdMember($signedUser3);
        $manager->persist($outing3);
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
