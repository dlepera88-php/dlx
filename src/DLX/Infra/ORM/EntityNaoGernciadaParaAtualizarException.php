<?php
/**
 * Created by PhpStorm.
 * User: dlepera88
 * Date: 30/11/2018
 * Time: 23:27
 */

namespace DLX\Infra\ORM\Exceptions;


use Throwable;

class EntityNaoGernciadaParaAtualizarException extends \Exception
{
    public function __construct(string $classe)
    {
        parent::__construct("A classe {$classe} não pode ser atualizada, pois não está sendo gerenciada pelo ORM.", 403);
    }
}