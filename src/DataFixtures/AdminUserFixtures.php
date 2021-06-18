<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Administrator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $player = new Administrator();
        $player->setEmail("admin@admin.com");

        $password = $this->encoder->encodePassword($player, 'admin');
        $player->setPassword($password);

        $player->setRoles(["ROLE_ADMIN"]);
        $manager->persist($player);
        $manager->flush();
    }
}
