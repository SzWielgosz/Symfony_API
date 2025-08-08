<?php

namespace App\Serializer\Normalizer;

use App\Entity\Composer;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class SymphonyNormalizer extends ObjectNormalizer
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        ?ClassMetadataFactoryInterface $classMetadataFactory = null,
        ?NameConverterInterface $nameConverter = null,
        ?PropertyAccessorInterface $propertyAccessor = null,
        ?PropertyTypeExtractorInterface $propertyTypeExtractor = null

    )
    {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        // Add handler circular reference
        $context[AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER] = function ($innerObject, string $format = null, array $innerContext = []) {
            // Return ID for inner ogjects that have getId() method
            return method_exists($innerObject, 'getId') ? $innerObject->getId() : null;
        };

        // Normalize the object
        $data = parent::normalize($object, $format, $context);

        return is_array($data) ? $data : [$data];
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        $symphony = parent::denormalize($data, $type, $format, $context);
        if (empty($data['composerId']) || empty($data['tagId'])){
            return $symphony;
        }

        # When symphony has a composer id then find the composer and set it
        $composer = $this->entityManager->getRepository(Composer::class)->find($data['composerId']);
        $tag = $this->entityManager->getRepository(Tag::class)->find($data['tagId']);


        if($composer){
            $symphony->setComposer($composer);
        }

        if($tag){
            $symphony->addTag($tag);
        }

        return $symphony;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof \App\Entity\Symphony;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type == \App\Entity\Symphony::class;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
