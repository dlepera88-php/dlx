<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 06/12/2018
 * Time: 16:45
 */

namespace DLX\Tests\Core\Services;


use DLX\Contracts\CommandInterface;
use DLX\Core\Services\CriarCommandByArray;
use DLX\Tests\Core\Exemplos\TesteCommand;
use PHPUnit\Framework\TestCase;

class CriarCommandByArrayTest extends TestCase
{
    /**
     * @return array
     */
    public function providerDados()
    {
        return [
            [['id' => 1, 'nome' => 'Teste1', 'grupos' => ['admin']]],
            [['id' => 2, 'nome' => 'Teste2', 'grupos' => ['usuario']]],
            [['id' => 3, 'nome' => 'Fulano da Silva', 'grupos' => ['admin', 'usuarios']]],
            [['id' => 12323, 'nome' => 'Outro Fulano', 'grupos' => ['convidado']]]
        ];
    }

    /**
     * @param array $dados
     * @dataProvider providerDados
     */
    public function test_executar_criarCommand(array $dados)
    {
        /** @var TesteCommand $command */
        $command = (new CriarCommandByArray(TesteCommand::class, $dados))->executar();

        $requestCommand = $command->getRequest();

        $this->assertInstanceOf(CommandInterface::class, $command);
        $this->assertEquals($dados['id'], $command->getId());
        $this->assertEquals($dados['nome'], $command->getNome());
        $this->assertEquals($dados['grupos'], $command->getGrupos());

        $this->assertArrayHasKey('id', $requestCommand);
        $this->assertArrayHasKey('nome', $requestCommand);
        $this->assertArrayHasKey('grupos', $requestCommand);
        $this->assertEquals($dados['id'], $requestCommand['id']);
        $this->assertEquals($dados['nome'], $requestCommand['nome']);
        $this->assertEquals($dados['grupos'], $requestCommand['grupos']);
    }
}