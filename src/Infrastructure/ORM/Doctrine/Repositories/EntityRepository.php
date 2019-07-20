<?php
/**
 * Created by PhpStorm.
 * User: dlepera88
 * Date: 30/11/2018
 * Time: 23:21
 */

namespace DLX\Infrastructure\ORM\Doctrine\Repositories;


use DLX\Domain\Entities\Entity;
use DLX\Domain\Repositories\EntityRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\UnitOfWork;

/**
 * Class EntityRepository
 * @package DLX\Infrastructure\ORM\Doctrine\Repositories
 * @covers EntityRepositoryTest
 */
class EntityRepository extends DoctrineEntityRepository implements EntityRepositoryInterface
{
    /**
     * Criar a persistência de dados de uma entidade
     * @param Entity $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Entity $entity): void
    {
        $this->_em->persist($entity);
        $this->_em->flush($entity);
    }

    /**
     * Atualizar a persistência de dados de uma entidade
     * @param Entity $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Entity $entity): void
    {
        $entity = $this->_em->merge($entity);
        $this->_em->flush($entity);
    }

    /**
     * Cria ou atualiza a persistência de dados de uma entidade
     * @param Entity $entity
     * @throws ORMException
     * @throws OptimisticLockException
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
     * @throws ORMException
     */
    public function delete(Entity $entity): void
    {
        $entity = $this->_em->merge($entity);
        $this->_em->remove($entity);
        $this->_em->flush($entity);
    }

    /**
     * Obter uma entidade referenciada e gerenciada pelo Doctrine
     * @param string $entity
     * @param $id
     * @return Entity|null
     * @throws ORMException
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

        /**
         * @param string $campo
         * @param $valor
         * @return Comparison
         */
        $getExprCriteria = function (string $campo, $valor) {
            return is_array($valor)
                ? Criteria::expr()->in($campo, $valor)
                : Criteria::expr()->contains($campo, $valor);
        };

        $loopCriteria = function (array $criteria, string $tipo) use ($getExprCriteria, &$qb) {
            foreach ($criteria as $campo => $valor) {
                $expr = $getExprCriteria($campo, $valor);
                $criteria = Criteria::create();
                $tipo === 'and' ? $criteria->andWhere($expr) : $criteria->orWhere($expr);
                $qb->addCriteria($criteria);
            }
        };

        // Adicionar wheres com AND
        if (array_key_exists('and', $criteria) && is_array($criteria['and'])) {
            $loopCriteria($criteria['and'], 'and');
            unset($criteria['and']);
        }

        // Adicionar wheres com OR
        if (array_key_exists('or', $criteria) && is_array($criteria['or'])) {
            $loopCriteria($criteria['or'], 'or');
            unset($criteria['or']);
        }

        // Se sobraram itens na criteria, são considerados com OR
        if (!empty($criteria)) {
            $loopCriteria($criteria, 'or');
            unset($criteria);
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

        $query = $qb->getQuery();

        return $query->getResult();
    }
}