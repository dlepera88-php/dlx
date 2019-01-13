<?php
/**
 * Created by PhpStorm.
 * User: dlepera88
 * Date: 30/11/2018
 * Time: 23:15
 */

namespace DLX\Domain\Repositories;


use DLX\Domain\Entities\Entity;

interface EntityRepositoryInterface
{
    /**
     * Criar a persistência de dados de uma entidade
     * @param Entity $entity
     */
    public function create(Entity $entity): void;

    /**
     * Atualizar a persistência de dados de uma entidade
     * @param Entity $entity
     */
    public function update(Entity $entity): void;

    /**
     * Cria ou atualiza a persistência de dados de uma entidade
     * @param Entity $entity
     */
    public function save(Entity $entity): void;

    /**
     * Excluir a persistência de dados de uma entidade
     * @param Entity $entity
     */
    public function delete(Entity $entity): void;

    /**
     * @param $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return mixed
     */
    public function find($id, $lockMode = null, $lockVersion = null);

    /**
     * @return array|null
     */
    public function findAll();

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     * @return array|null
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return mixed
     */
    public function findOneBy(array $criteria, array $orderBy = null);

    /**
     * @param string $entity
     * @param $id
     * @return Entity|null
     */
    public function getReference(string $entity, $id): ?Entity;
}