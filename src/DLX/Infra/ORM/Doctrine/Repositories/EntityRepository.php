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
use DLX\Infra\ORM\Exceptions\EntityNaoGernciadaParaAtualizarException;
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
        if ($this->_em->getUnitOfWork()->getEntityState($entity) !== UnitOfWork::STATE_MANAGED) {
            throw new EntityNaoGernciadaParaAtualizarException(get_class($entity));
        }

        $this->_em->merge($entity);
        $this->_em->flush($entity);
    }

    /**
     * Cria ou atualiza a persistência de dados de uma entidade
     * @param Entity $entity
     * @throws EntityNaoGernciadaParaAtualizarException
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
    }
}