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

namespace DLX\Infra\ORM\Doctrine\Services;


use DLX\Contracts\TransacaoInterface;
use Doctrine\ORM\EntityManager;

class TransacaoDoctrine implements TransacaoInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * TransacaoDoctrine constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Iniciar uma transação
     * @throws \Doctrine\ORM\ORMException
     */
    public function begin(): void
    {
        $this->em->beginTransaction();
    }

    /**
     * Dar rollback em uma transação
     * @throws \Doctrine\ORM\ORMException
     */
    public function rollback(): void
    {
        $this->em->rollback();
    }

    /**
     * Comitar uma transação
     * @throws \Doctrine\ORM\ORMException
     */
    public function commit(): void
    {
        $this->em->commit();
    }

    /**
     * Executar uma função dentro de uma transação
     * @param callable $func
     * @return mixed
     * @throws \Throwable
     */
    public function transactional(callable $func)
    {
        return $this->em->transactional($func);
    }
}