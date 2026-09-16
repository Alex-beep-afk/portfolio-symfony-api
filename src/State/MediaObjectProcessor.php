<?php
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\MediaObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Ce "Processor" est appelé par API Platform lors d'une requête de mutation 
 * (ici, la création d'un MediaObject via une requête POST).
 * 
 * Son rôle principal : Remplacer le comportement par défaut d'API Platform 
 * pour gérer manuellement la réception d'un fichier physique (upload) via un 
 * formulaire (multipart/form-data), plutôt que du texte JSON classique.
 */
final class MediaObjectProcessor implements ProcessorInterface
{
    /**
     * @param RequestStack $requestStack Permet d'accéder à la requête HTTP actuelle pour y extraire le fichier.
     * @param EntityManagerInterface $entityManager Permet de sauvegarder l'entité en base de données (Doctrine).
     */
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Cette méthode contient la logique d'enregistrement.
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        // 1. On récupère la requête HTTP en cours
        $request = $this->requestStack->getCurrentRequest();
        
        // 2. On extrait le fichier physique depuis les données du formulaire.
        // On s'attend à ce que le champ du formulaire s'appelle 'file' (c'est ce que Nuxt devra envoyer).
        $uploadedFile = $request->files->get('file');

        // 3. Si aucun fichier n'a été envoyé avec la clé 'file', on bloque tout et on renvoie une erreur 400.
        if (!$uploadedFile) {
            throw new BadRequestHttpException('Le fichier "file" est obligatoire.');
        }

        // 4. On crée manuellement une nouvelle instance de notre entité MediaObject.
        // Habituellement API Platform le fait tout seul avec du JSON, mais ici on le fait à la main.
        $mediaObject = new MediaObject();

        // 5. On injecte le fichier uploadé dans la propriété 'file' de notre entité.
        // C'est cette action qui va réveiller VichUploader (en coulisses) pour qu'il 
        // déplace physiquement le fichier dans le bon dossier.
        $mediaObject->file = $uploadedFile;

        // 6. On dit à Doctrine de préparer la sauvegarde de ce nouvel objet
        $this->entityManager->persist($mediaObject);
        // 7. On exécute la sauvegarde en base de données (ce qui déclenche aussi VichUploader)
        $this->entityManager->flush();

        // 8. On renvoie l'objet fraîchement créé. 
        // API Platform va ensuite le passer au Normalizer (MediaObjectNormalizer !) 
        // pour le transformer en JSON et l'envoyer au client.
        return $mediaObject;
    }
}
