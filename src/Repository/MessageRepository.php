<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * Trouve tous les messages où l'utilisateur est l'émetteur ou le destinataire
     * @param User $user
     * @return Message[] Returns an array of Message objects
     */
    public function findMessagesForUser(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.emetteur = :user')
            ->orWhere('m.destinataire = :user')
            ->setParameter('user', $user)
            ->orderBy('m.dateheure', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
