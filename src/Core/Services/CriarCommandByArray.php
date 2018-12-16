<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 06/12/2018
 * Time: 16:39
 */

namespace DLX\Core\Services;

use DLX\Contracts\ServiceInterface;

class CriarCommandByArray implements ServiceInterface
{
    /** @var string */
    private $command;
    /** @var array */
    private $dados = [];

    public function __construct(string $nome_command, array $dados = [])
    {
        $this->command = $nome_command;
        $this->dados = $dados;
    }

    /**
     * Executar o serviço.
     * @return mixed
     */
    public function executar()
    {
        $command = new $this->command;

        // TODO: CRIAR OU USAR UM HYDRATOR PARA FAZER ESSES SET
        foreach ($this->dados as $nome => $valor) {
            $metodoSet = 'set' . str_replace(' ', '', ucwords(implode(explode('-', implode(' ', explode('_', $nome))))));

            if (method_exists($command, $metodoSet)) {
                $command->{$metodoSet}($valor);
            }
        }

        return $command;
    }
}