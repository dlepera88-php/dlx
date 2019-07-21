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
use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Func;
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
         * @param $valor
         * @param string $campo
         * @return Comparison|Func
         */
        $createExpr = function ($valor, string $campo) use ($qb) {
            $expr = $qb->expr();
            $campo = "e.{$campo}";

            switch(gettype($valor)) {
                case 'array': $comparacao = $expr->in($campo, $valor); break;
                case 'boolean': $comparacao =  $expr->eq($campo, (int)$valor); break;
                default: $comparacao = $expr->like($campo, $valor);
            }

            return $comparacao;
        };

        /**
         * @param array $criteria
         * @param string $tipo
         */
        $loopCriteria = function (array $criteria, string $tipo) use ($createExpr, &$qb) {
            $lista_expr = [];

            foreach ($criteria as $campo => $valor) {
                $lista_expr[] = $createExpr($valor, $campo);
            }
            // array_walk($criteria, $createExpr);

            $clausula_where = $tipo === 'and'
                ? call_user_func_array([$qb->expr(), 'andX'], $lista_expr)
                : call_user_func_array([$qb->expr(), 'orX'], $lista_expr);

            $qb->where($clausula_where);
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