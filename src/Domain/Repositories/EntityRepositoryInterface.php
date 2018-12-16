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
}