<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 PHP DLX
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace DLX\Infrastructure;


use DLX\Core\Configure;
use Doctrine\ORM\EntityManager;

class EntityManagerX
{
    /** @var EntityManager */
    private static $em;

    /**
     * Constructor EntityManagerX.
     * Para não precisar instanciar o EntityManager sempre que esse método for executado, armazeno a instância e só
     * instancio quando necessário.
     * @param null|string $tipo_em
     * @param array|null $conexao
     * @param array|null $config
     * @return \Doctrine\ORM\EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getInstance(
        ?string $tipo_em = null,
        ?array $conexao = null,
        ?array $config = null
    ) {
        if (is_null(self::$em) || (!empty($tipo_em) || !empty($config) || !empty($config))) {
            $tipo_em = $tipo_em ?? Configure::get('bd', 'orm');
            $conexao = $conexao ?? Configure::get('bd', 'conexao');
            $config = $config ?? Configure::get('bd');

            self::$em = EntityManagerFactory::create($tipo_em, $conexao, $config);
        }

        return self::$em;
    }

    /**
     * Instanciar a repository de uma entidade.
     * @param string $entity
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(string $entity)
    {
        return self::getInstance()->getRepository($entity);
    }

    /**
     * Instânciar uma entidade apenas com o código ID, para usar como referência
     * @param string $entity
     * @param $id
     * @return bool|\Doctrine\Common\Proxy\Proxy|null|object
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getReference(string $entity, $id)
    {
        return self::getInstance()->getReference($entity, $id);
    }

    /**
     * Iniciar uma transação
     * @throws \Doctrine\ORM\ORMException
     */
    public static function beginTransaction(): void
    {
        self::getInstance()->beginTransaction();
    }

    /**
     * Fazer o rollback de uma transação
     * @throws \Doctrine\ORM\ORMException
     */
    public static function rollback()
    {
        self::getInstance()->rollback();
    }

    /**
     * Confirmar uma transação
     * @throws \Doctrine\ORM\ORMException
     */
    public static function commit()
    {
        self::getInstance()->commit();
    }
}