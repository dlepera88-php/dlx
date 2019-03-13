<?php
/**
 * Created by PhpStorm.
 * User: dlepera88
 * Date: 30/11/2018
 * Time: 23:21
 */

namespace DLX\Infra\ORM\Doctrine\Repositories;


use DLX\Domain\Entities\Entity;
use DLX\Domain\Repositories\EntityRepositoryInterface;
use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use Doctrine\ORM\UnitOfWork;

class EntityRepository extends DoctrineEntityRepository implements EntityRepositoryInterface
{
    /**
     * Criar a persistência de dados de uma entidade
     * @param Entity $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Entity $entity): void
    {
        $this->_em->persist($entity);
        $this->_em->flush($entity);
    }

    /**
     * Atualizar a persistência de dados de uma entidade
     * @param Entity $entity
     * @throws EntityNaoGernciadaParaAtualizarException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Entity $entity): void
    {
        $this->_em->merge($entity);
        $this->_em->flush($entity);
    }

    /**
     * Cria ou atualiza a persistência de dados de uma entidade
     * @param Entity $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Entity $entity): void
    {
        if ($this->_em->getUnitOfWork()->getEntityState($entity) !== UnitOfWork::STATE_MANAGED) {
            $this->create($entity);
        } else {
            $this->update($entity);
        }
    }

    /**
     * Excluir a persistência de dados de uma entidade
     * @param Entity $entity
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete(Entity $entity): void
    {
        $this->_em->remove($entity);
        $this->_em->flush($entity);
    }

    /**
     * Obter uma entidade referenciada e gerenciada pelo Doctrine
     * @param string $entity
     * @param $id
     * @return Entity|null
     * @throws \Doctrine\ORM\ORMException
     */
    public function getReference(string $entity, $id): ?Entity
    {
        return $this->_em->getReference($entity, $id);
    }

    /**
     * Utilizar o findBy com like
     * @param array $criteria
     * @param array $order_by
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findByLike(array $criteria = [], array $order_by = [], ?int $limit = null, ?int $offset = null): array
    {
        $qb = $this->createQueryBuilder('e');

        foreach ($criteria as $campo => $valor) {
            if (is_array($valor)) {
                $qb->orWhere("e.{$campo} in (" . implode(',', $valor) . ")");
            } else {
                $qb->orWhere("e.{$campo} like '%{$valor}%'");
            }
        }

        foreach ($order_by as $ordem => $tipo) {
            $qb->addOrderBy($ordem, $tipo);
        }

        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        if ($offset > 0) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }
}