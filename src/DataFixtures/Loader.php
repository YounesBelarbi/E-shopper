<?php

namespace App\DataFixtures;

use Nelmio\Alice\Faker\Provider\AliceProvider;
use Nelmio\Alice\Loader\NativeLoader;
use Faker\Factory as FakerGeneratorFactory;
use Faker\Generator as FakerGenerator;
use App\DataFixtures\Faker\Provider;

class Loader extends NativeLoader
{
    protected function createFakerGenerator(): FakerGenerator
    {
        $generator = FakerGeneratorFactory::create(parent::LOCALE);
        $generator->addProvider(new AliceProvider());

        //add new provider
        $generator->addProvider(new Provider($generator));
        $generator->seed($this->getSeed());

        $generator->addProvider(new Provider($generator));

        return $generator;
    }
}
