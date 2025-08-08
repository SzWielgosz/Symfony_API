<?php

namespace App\DataFixtures;

use App\Entity\Composer;
use App\Entity\Symphony;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Load tags for symphonies
        $tags = [
            'Baroque',
            'Classical',
            'Romantic',
            'Modernism',
            'Neoclassicism',
            'Impressionism',
            'Expressionism',
            'Choral Music',
            'Orchestral Music',
            'Symphonic Poem',
            'Opera',
        ];
        foreach ($tags as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $manager->persist($tag);
        }
        $manager->flush();

        // Load composers and their symphonies 
        $composer1 = new Composer();
        $composer1->setFirstName('Ludwig');
        $composer1->setLastName('Beethoven');
        $composer1->setDateOfBirth(new \DateTimeImmutable('1770-12-17'));
        $composer1->setCountryCode('DE');
        $manager->persist($composer1);

        $symphony1 = new Symphony();
        $symphony1->setName('Symphony No. 5');
        $symphony1->setDescription('One of the most famous compositions in classical music.');
        $symphony1->setComposer($composer1);
        $symphony1->addTag($manager->getRepository(Tag::class)->findOneBy(['name' => 'Romantic']));
        $manager->persist($symphony1);

        $symphony2 = new Symphony();
        $symphony2->setName('Symphony No. 9');
        $symphony2->setDescription('Includes the famous "Ode to Joy" choral finale.');
        $symphony2->setComposer($composer1);
        $symphony1->addTag($manager->getRepository(Tag::class)->findOneBy(['name' => 'Classical']));
        $manager->persist($symphony2);

        $composer2 = new Composer();
        $composer2->setFirstName('Wolfgang');
        $composer2->setLastName('Mozart');
        $composer2->setDateOfBirth(new \DateTimeImmutable('1756-01-27'));
        $composer2->setCountryCode('AT');
        $manager->persist($composer2);

        $symphony3 = new Symphony();
        $symphony3->setName('Symphony No. 40');
        $symphony3->setDescription('One of Mozart\'s most admired works.');
        $symphony3->setComposer($composer2);
        $symphony1->addTag($manager->getRepository(Tag::class)->findOneBy(['name' => 'Baroque']));
        $manager->persist($symphony3);

        $composer3 = new Composer();
        $composer3->setFirstName('Pyotr');
        $composer3->setLastName('Tchaikovsky');
        $composer3->setDateOfBirth(new \DateTimeImmutable('1840-05-07'));
        $composer3->setCountryCode('RU');
        $manager->persist($composer3);

        $symphony4 = new Symphony();
        $symphony4->setName('Symphony No. 6 "Pathétique"');
        $symphony4->setDescription('Tchaikovsky\'s final symphony, full of emotion.');
        $symphony4->setComposer($composer3);
                $symphony1->addTag($manager->getRepository(Tag::class)->findOneBy(['name' => 'Opera']));
        $manager->persist($symphony4);

        $manager->flush();
    }
}