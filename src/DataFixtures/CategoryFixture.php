<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixture extends Fixture
{
    const CAT1 = 'category1';
    const CAT2 = 'category2';
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $category = new Category();
        $category->setName($faker->word(3));
        $category->setDescription($faker->sentence(2));
        $category->setColor('warning');
        $manager->persist($category);
        $this->addReference(self::CAT1, $category);

        $category = new Category();
        $category->setName($faker->word(3));
        $category->setDescription($faker->sentence(2));
        $category->setColor('warning');
        $manager->persist($category);
        $this->addReference(self::CAT2, $category);

        $manager->flush();
    }
}
