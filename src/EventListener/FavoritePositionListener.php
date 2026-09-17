<?php

namespace App\EventListener;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

// Cet attribut indique à Symfony que cette classe "écoute" un événement Doctrine.
// Ici, on écoute l'événement "onFlush", qui se déclenche juste avant que Doctrine
// n'envoie toutes les requêtes SQL (INSERT, UPDATE) à la base de données.
#[AsDoctrineListener(event: Events::onFlush)]
class FavoritePositionListener
{
    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        // L'EntityManager (em) permet de manipuler les entités et d'interagir avec la base de données.
        $em = $eventArgs->getObjectManager();
        
        // L'UnitOfWork (uow) est le système interne de Doctrine qui garde une trace
        // de tout ce qui a été modifié, ajouté ou supprimé avant l'enregistrement (flush).
        $uow = $em->getUnitOfWork();

        // On récupère toutes les entités qui sont sur le point d'être créées (Insertions)
        // ou modifiées (Updates) dans la base de données lors de ce flush.
        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates()
        );

        // On boucle sur toutes ces entités en attente de sauvegarde
        foreach ($entities as $entity) {
            // Si l'entité qu'on est en train de sauvegarder n'est pas un Project, 
            // on l'ignore et on passe à la suivante.
            if (!$entity instanceof Project) {
                continue;
            }

            // On récupère la position favorite demandée pour ce projet (ex: 1, 2, 3, 4 ou null)
            $position = $entity->getFavoritePosition();
            
            // Si le projet n'a pas de position (n'est pas favori), on l'ignore, pas de conflit possible.
            if ($position === null) {
                continue;
            }

            // On demande à Doctrine de nous lister uniquement les champs qui ont été modifiés.
            $changeSet = $uow->getEntityChangeSet($entity);
            
            // On vérifie si c'est un nouveau projet (l'ID vient d'être créé) OU si la position a changé.
            // Cela évite de faire tourner le script si on a juste modifié le titre du projet par exemple.
            if (isset($changeSet['id']) || isset($changeSet['favoritePosition'])) {
                
                // On cherche dans la base de données s'il y a DÉJÀ un projet qui possède cette même position.
                $existingProject = $em->getRepository(Project::class)->findOneBy(['favoritePosition' => $position]);

                // S'il y a bien un projet existant à cette place, et que ce n'est pas le projet actuel...
                if ($existingProject && $existingProject !== $entity) {
                    
                    // ... on retire la position de l'ancien projet (il perd son statut de favori)
                    $existingProject->setFavoritePosition(null);
                    
                    // On prépare l'ancien projet à être sauvegardé lui aussi
                    $em->persist($existingProject);
                    
                    // Comme l'événement "onFlush" se passe APRÈS que Doctrine ait préparé ses requêtes,
                    // on est obligé de forcer Doctrine (UnitOfWork) à recalculer les modifications
                    // pour prendre en compte le "null" qu'on vient d'affecter à l'ancien projet.
                    $meta = $em->getClassMetadata(Project::class);
                    $uow->computeChangeSet($meta, $existingProject);
                }
            }
        }
    }
}
