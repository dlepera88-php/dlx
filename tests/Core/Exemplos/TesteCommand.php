<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 06/12/2018
 * Time: 17:06
 */

namespace DLX\Tests\Core\Exemplos;


use DLX\Contracts\CommandInterface;

class TesteCommand implements CommandInterface
{
    /** @var int */
    private $id;
    /** @var string */
    private $nome;
    /** @var string[] */
    private $grupos;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return TesteCommand
     */
    public function setId(int $id): TesteCommand
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return TesteCommand
     */
    public function setNome(string $nome): TesteCommand
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getGrupos(): array
    {
        return $this->grupos;
    }

    /**
     * @param string[] $grupos
     * @return TesteCommand
     */
    public function setGrupos(array $grupos): TesteCommand
    {
        $this->grupos = $grupos;
        return $this;
    }

    /**
     * Request completa do comando
     * @return array Retorna um array associativo. A chave é o nome da propriedade e o valor seu respectivo valor
     */
    public function getRequest(): array
    {
        return [
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'grupos' => $this->getGrupos()
        ];
    }
}