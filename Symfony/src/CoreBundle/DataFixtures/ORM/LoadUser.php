<?php

namespace CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CoreBundle\Entity\User;

class LoadUser implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Usernames to create
        $listNames = array('Fiona', 'Noellie', 'Fred', 'Leo');

        foreach ($listNames as $name) {
            // Create username
            $user = new User;

            // Username and password are identicals for now
            $user->setUsername($name);
            $user->setPassword($name);

            $user->setProfilePicture('');

            // We don't use salt yet
            $user->setSalt('');

            if ($name == 'Fiona'){
                $user->setRoles(['ROLE_ADMIN']);
            } elseif ($name == 'Noellie'){
                $user->setRoles(['ROLE_PRO']);
            } else {
                $user->setRoles(['ROLE_AMATEUR']);
            }

            // We persist
            $manager->persist($user);
        }

        // We flush
        $manager->flush();
    }
}