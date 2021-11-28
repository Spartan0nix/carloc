<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @property EntityManagerInterface $em
 */
trait FixtureTrait
{
    /**
     * @param array<string> $fixtures
     * @param ContainerInterface $container
     * @return array
     */
    public function load(array $fixtures, ContainerInterface $container): array {
        $files = [];
        $fixture_path = join(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures']);

        foreach($fixtures as $fixture){
            // print($fixture);
            $file_name = $fixture.'.yaml';
            $file_path = join(DIRECTORY_SEPARATOR, [$fixture_path, $file_name]);
            array_push($files, $file_path);
        }

        /** @var LoaderInterface $loader */
        $loader = $container->get('fidry_alice_data_fixtures.loader.doctrine');

        return $loader->load($files);
    }
}