<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 04/12/2018
 * Time: 16:46
 */

namespace DLX\Infrastructure;

use DLX\Infrastructure\Exceptions\EntityManagerNaoEncontradoException;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Logging\EchoSQLLogger;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Exception;

class EntityManagerFactory
{
    const ORM_DOCTRINE = 'doctrine';

    /**
     * Criar uma instância do EntityManager a ser utilizado de acordo com a configuração.
     * @param string $tipo_em Tipo de EntityManager
     * @param array $conexao
     * @param array $config
     * @return DoctrineEntityManager
     * @throws ORMException
     * @throws Exception
     */
    public static function create(string $tipo_em, array $conexao, array $config)
    {
        switch ($tipo_em) {
            case self::ORM_DOCTRINE:
                $doctrine_config = self::getDoctrineConfig($config['mapping'], $config['dir'], $config['dev-mode']);

                if (array_key_exists('debug', $config) && $config['debug']) {
                    $doctrine_config->setSQLLogger(new $config['debug']);
                }

                /** @var EntityManager $em */
                $em = DoctrineEntityManager::create($conexao, $doctrine_config);

                if (array_key_exists('types', $config)) {
                    self::adicionarDoctrineTypes($em, $config['types']);
                }

                return $em;
                break;
            default:
                throw new EntityManagerNaoEncontradoException($tipo_em);
        }
    }

    /**
     * Retorna a configuração necessária do doctrine para gerar o Entity Manager
     * @param string $tipo
     * @param array $dir
     * @param bool $dev_mode
     * @return Configuration
     */
    private static function getDoctrineConfig(string $tipo, array $dir, bool $dev_mode = true): Configuration
    {
        switch ($tipo) {
            case 'yaml':
                $doctrine_config = Setup::createYAMLMetadataConfiguration($dir, $dev_mode);
                break;
            case 'xml':
                $doctrine_config = Setup::createXMLMetadataConfiguration($dir, $dev_mode);
                break;
            default:
                $doctrine_config = Setup::createAnnotationMetadataConfiguration($dir, $dev_mode);
        }

        return $doctrine_config;
    }

    /**
     * Adicionar os tipos de dados no doctrine
     * @param DoctrineEntityManager $em
     * @param array $types
     * @throws DBALException
     */
    private static function adicionarDoctrineTypes(EntityManager $em, array $types): void
    {
        foreach ($types as $nome => $type) {
            Type::addType($nome, $type);
            $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping($nome, $nome);
        }
    }
}