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
    /** @var Configure */
    private static $configure;

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$configure = new Configure('dlx', Configure::DEV);
        self::$configure->carregarConfiguracao('Exemplos/configuracao.php');
    }


    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     */
    public function test_carregarConfiguracao_com_arquivo_invalido()
    {
        $this->expectException(ArquivoConfiguracaoNaoEncontradoException::class);
        self::$configure->carregarConfiguracao('teste.php');
    }

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     */
    public function test_carregarConfiguracao_com_arquivo_exemplo()
    {
        $this->assertArrayHasKey('dlx', $_ENV);
    }

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     */
    public function test_get_configuracao_nao_existe()
    {
        $this->assertNull(self::$configure->get('teste'));
    }

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     */
    public function test_get_configuracao_valida()
    {
        $this->assertNotNull(self::$configure->get('bd'));
    }

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     */
    public function test_get_configuracao_mais_de_um_nivel()
    {
        $conf = self::$configure->get('bd', 'orm');

        $this->assertNotNull($conf);
        $this->assertEquals($conf, 'doctrine');
    }
}