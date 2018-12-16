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
use DLX\Core\Exceptions\ArquivoConfiguracaoNaoInformadoException;

class Configure
{
    const PRODUCAO = 'prod';
    const HOMOLOGACAO = 'homol';
    const DEV = 'dev';

    /** @var string */
    private static $ambiente;
    /** @var string */
    private static $tipo;

    /**
     * @return string
     */
    public static function getAmbiente(): string
    {
        return self::$ambiente;
    }

    /**
     * @return string
     */
    public static function getTipo(): string
    {
        return self::$tipo;
    }

    /**
     * Iniciar configuração de um determinado ambiente.
     * @param string $ambiente
     * @param string $arquivo |null
     * @throws ArquivoConfiguracaoNaoEncontradoException
     * @throws ArquivoConfiguracaoNaoInformadoException
     */
    public static function init(string $ambiente, ?string $arquivo = null)
    {
        self::$ambiente = $ambiente;

        if (!self::hasAmbiente($ambiente)) {
            if (empty($arquivo)) {
                throw new ArquivoConfiguracaoNaoInformadoException($ambiente);
            }

            self::carregarConfiguracao($arquivo);
        }

        // Verificar a configuração carregada e setar o tipo de ambiente
        // O tipo do ambiente deve ser informado na configutação 'tipo-ambiente'
        // Quando nenhum tipo de ambiente é informado, o sistema assume que é um ambiente de desenvolvimento
        self::$tipo = self::get('tipo-ambiente') ?? self::DEV;
    }

    /**
     * Verifica se o ambiente foi onfigurado como um ambiente produção
     * @return bool
     */
    public static function isProducao(): bool
    {
        return self::$tipo === self::PRODUCAO;
    }

    /**
     * Verifica se o ambiente foi configurado como um ambiente de homologação
     * @return bool
     */
    public static function isHomologacao(): bool
    {
        return self::$tipo === self::HOMOLOGACAO;
    }

    /**
     * Verifica se o ambiente foi configurado como um ambiente de desenvolvimento.
     * @return bool
     */
    public static function isDev(): bool
    {
        return self::$tipo === self::DEV;
    }

    /**
     * Verificar se um determinado ambiente já foi iniciado.
     * @param string $ambiente
     * @return bool
     */
    public static function hasAmbiente(string $ambiente): bool
    {
        return array_key_exists($ambiente, $_ENV);
    }

    /**
     * Trocar o apontamento do Configure para outro ambiente
     * @param string $novo_ambiente
     * @throws ArquivoConfiguracaoNaoEncontradoException
     * @throws ArquivoConfiguracaoNaoInformadoException
     */
    public static function trocarAmbiente(string $novo_ambiente): void
    {
        self::init($novo_ambiente);
    }

    /**
     * Obter o valor de uma determinada configuração.
     * @param string $configs Nomes das configurações aninhadas.
     * @return mixed|null
     */
    public static function get(string ...$configs)
    {
        if (!self::hasAmbiente(self::$ambiente)) {
            return null;
        }

        $conf_desejada = $_ENV[self::$ambiente];

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
    public static function carregarConfiguracao(string $arquivo_configuracao): void
    {
        if (!file_exists($arquivo_configuracao)) {
            throw new ArquivoConfiguracaoNaoEncontradoException($arquivo_configuracao);
        }

        $_ENV[self::$ambiente] = include_once $arquivo_configuracao;
    }
}