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

namespace DLX\Core;


use DLX\Core\Exceptions\ArquivoConfiguracaoNaoEncontradoException;

class Configure
{
    const PRODUCAO = 'prod';
    const HOMOLOGACAO = 'homol';
    const DEV = 'dev';

    /** @var string */
    private $ambiente;
    /** @var string */
    private $tipo;

    /**
     * @return string
     */
    public function getAmbiente(): string
    {
        return $this->ambiente;
    }

    /**
     * @return string
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function __construct(string $ambiente, string $tipo = self::DEV)
    {
        $this->ambiente = $ambiente;
        $this->tipo = $tipo;
    }

    /**
     * Verifica se o ambiente foi onfigurado como um ambiente produção
     * @return bool
     */
    public function isProducao(): bool
    {
        return $this->tipo === self::PRODUCAO;
    }

    /**
     * Verifica se o ambiente foi configurado como um ambiente de homologação
     * @return bool
     */
    public function isHomologacao(): bool
    {
        return $this->tipo === self::HOMOLOGACAO;
    }

    /**
     * Verifica se o ambiente foi configurado como um ambiente de desenvolvimento.
     * @return bool
     */
    public function isDev(): bool
    {
        return $this->tipo === self::DEV;
    }

    /**
     * Obter o valor de uma determinada configuração.
     * @param string $configs Nomes das configurações aninhadas.
     * @return mixed|null
     */
    public function get(string ...$configs)
    {
        if (!array_key_exists($this->ambiente, $_ENV)) {
            return null;
        }

        $conf_desejada = $_ENV[$this->ambiente];

        foreach ($configs as $conf) {
            if (!array_key_exists($conf, $conf_desejada)) {
                return null;
            }

            $conf_desejada = $conf_desejada[$conf];
        }

        return $conf_desejada;
    }

    /**
     * Carregar configurações do arquivo de configuração.
     * @param string $arquivo_configuracao
     * @throws ArquivoConfiguracaoNaoEncontradoException
     */
    public function carregarConfiguracao(string $arquivo_configuracao): void
    {
        if (!file_exists($arquivo_configuracao)) {
            throw new ArquivoConfiguracaoNaoEncontradoException($arquivo_configuracao);
        }

        $_ENV[$this->ambiente] = include_once $arquivo_configuracao;
    }
}