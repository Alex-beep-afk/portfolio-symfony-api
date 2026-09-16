<?php
namespace App\Serializer;

use App\Entity\MediaObject;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * Ce "Normalizer" est appelé par API Platform / Symfony juste avant de convertir 
 * un objet en JSON pour l'envoyer au frontend.
 * 
 * Son rôle principal : Intercepter la conversion de l'entité MediaObject pour 
 * calculer et injecter l'URL publique de l'image (le champ `contentUrl`) 
 * avant que la réponse ne parte vers Nuxt.
 */
final class MediaObjectNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    // Constante utilisée comme "drapeau" (flag) pour éviter une boucle infinie de normalisation.
    private const ALREADY_CALLED = 'MEDIA_OBJECT_NORMALIZER_ALREADY_CALLED';

    /**
     * @param StorageInterface $storage Le service de VichUploader qui permet de 
     *                                  retrouver le chemin public d'un fichier uploadé.
     */
    public function __construct(private StorageInterface $storage)
    {
    }

    /**
     * Méthode appelée pour transformer l'objet.
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        // 1. On marque dans le contexte qu'on a déjà traité cet objet.
        // Cela empêche la méthode de s'appeler elle-même en boucle à l'étape 3.
        $context[self::ALREADY_CALLED] = true;

        // 2. On génère l'URL publique du fichier associé à la propriété 'file' 
        // de notre MediaObject grâce à VichUploader.
        // On assigne ensuite cette URL à la propriété contentUrl.
        $object->contentUrl = $this->storage->resolveUri($object, 'file');

        // 3. On repasse la main au Normalizer principal de Symfony pour qu'il 
        // finisse le travail (transformer le reste des propriétés de l'objet en tableau).
        // C'est ici que ça bouclerait à l'infini si on n'avait pas le flag ALREADY_CALLED.
        return $this->normalizer->normalize($object, $format, $context);
    }

    /**
     * Symfony demande à cette méthode : "Es-tu capable de transformer cet objet ?"
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        // Si on a déjà marqué cet objet comme "traité" (voir étape 1 de normalize()), 
        // on dit à Symfony "non je ne m'en occupe plus", ce qui laisse le Normalizer 
        // normal de Symfony prendre le relais.
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        // On répond "oui" uniquement si l'objet à transformer est bien un MediaObject.
        return $data instanceof MediaObject;
    }

    /**
     * Indique à Symfony quels types de classes ce normalizer supporte.
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            // LE SECRET EST ICI : on met "false" pour dire à Symfony de NE PAS mettre
            // la décision en cache, l'obligeant à vérifier ALREADY_CALLED dans
            // supportsNormalization() à chaque fois !
            MediaObject::class => false,
        ];
    }
}
