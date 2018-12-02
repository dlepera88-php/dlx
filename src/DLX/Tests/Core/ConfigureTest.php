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


use DLX\Core\Configure;
use DLX\Core\Exceptions\ArquivoConfiguracaoNaoEncontradoException;
use PHPUnit\Framework\TestCase;

class ConfigureTest extends TestCase
{
    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     */
    public function test_carregarConfiguracao_com_arquivo_invalido()
    {
        $this->expectException(ArquivoConfiguracaoNaoEncontradoException::class);

        $configure = new Configure('dlx', Configure::DEV);
        $configure->carregarConfiguracao('teste.php');
    }

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     */
    public function test_carregarConfiguracao_com_arquivo_exemplo()
    {
        $configure = new Configure('dlx', Configure::DEV);
        $configure->carregarConfiguracao('Exemplos/configuracao.php');

        $this->assertArrayHasKey('dlx', $_ENV);
    }

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     */
    public function test_get_configuracao_nao_existe()
    {
        $configure = new Configure('dlx', Configure::DEV);
        $configure->carregarConfiguracao('Exemplos/configuracao.php');

        $this->assertNull($configure->get('teste'));
    }

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     */
    public function test_get_configuracao_valida()
    {
        $configure = new Configure('dlx', Configure::DEV);
        $configure->carregarConfiguracao('Exemplos/configuracao.php');

        $this->assertNotNull($configure->get('bd'));
    }
}