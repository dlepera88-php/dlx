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

namespace DLX\Tests\Core;

use DLX\Core\CommandBus\CommandBusAdapter;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;

class TesteCommand {
    public $info;

    public function __construct(string $info)
    {
        $this->info = $info;
    }
}


class TesteHandler {
    public function __invoke(TesteCommand $command)
    {
        $this->handle($command);
    }

    public function handle(TesteCommand $command)
    {
        echo $command->info;
    }
}

class TesteMelhoradoHandler extends TesteHandler {
    public $detalhes;

    public function __construct(string $detalhes)
    {
        $this->detalhes = $detalhes;
    }

    public function handle(TesteCommand $command)
    {
        parent::handle($command);
        echo "\n{$this->detalhes}";
    }
}

class CommandBusAdapterTest extends TestCase
{

    public function test_Create()
    {
        $mapping = [
            TesteCommand::class => TesteHandler::class
        ];

        $commandBus = CommandBusAdapter::create($mapping);
        $this->assertInstanceOf(CommandBus::class, $commandBus);
    }

    public function test_comandBus_handle_sem_dependencias()
    {
        $mapping = [
            TesteCommand::class => TesteHandler::class
        ];

        $this->expectOutputString('Teste executado com sucesso!');
        $commandBus = CommandBusAdapter::create($mapping);
        $commandBus->handle(new TesteCommand('Teste executado com sucesso!'));
    }

    public function test_commandBus_handle_com_dependencias()
    {
        $this->markTestSkipped();

        // TODO: implementar injetor de dependências

        $mapping = [
            TesteCommand::class => TesteMelhoradoHandler::class
        ];

        $this->expectOutputString('Teste executado com sucesso!');
        $commandBus = CommandBusAdapter::create($mapping);
        $commandBus->handle(new TesteCommand('Teste executado com sucesso!'));
    }
}
