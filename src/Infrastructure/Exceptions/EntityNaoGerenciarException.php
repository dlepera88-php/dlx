<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/01/2019
 * Time: 16:23
 */

namespace DLX\Infrastructure\Exceptions;


use DLX\Core\Exceptions\SystemException;

class EntityNaoGerenciarException extends SystemException
{
    /**
     * EntityNaoGerenciarException constructor.
     * @param string $entity
     */
    public function __construct(string $entity)
    {
        parent::__construct("A entidade {$entity} n�o est� gerenciada e n�o pode ser alterada ou exclu�da.");
    }
}