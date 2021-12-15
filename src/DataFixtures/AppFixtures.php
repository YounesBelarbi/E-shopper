<?php

namespace App\DataFixtures;

use App\DataFixtures\Loader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        // $loader = new NativeLoader();
        // $entities = $loader->loadFile(__DIR__ . '/fixtures.yaml')->getObjects();

        $loader = new Loader();
        $entities = $loader->loadFile(__DIR__ . '/fixtures.yaml')->getObjects();

        foreach ($entities as $entity) {
            $manager->persist($entity);
        };

        $manager->flush();
    }
}
