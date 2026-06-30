<?php
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\MediaObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class MediaObjectProcessor implements ProcessorInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $request = $this->requestStack->getCurrentRequest();
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            throw new BadRequestHttpException('Le fichier "file" est obligatoire.');
        }

        $mediaObject = new MediaObject();

        $mediaObject->file = $uploadedFile;

        // On sauvegarde directement en base de données
        $this->entityManager->persist($mediaObject);
        $this->entityManager->flush();

        return $mediaObject; // On renvoie l'objet, API Platform s'occupera d'afficher le JSON !
    }
}
