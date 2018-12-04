<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 04/12/2018
 * Time: 16:46
 */

namespace DLX\Infra;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager as DoctrineEntityManager;

class EntityManagerFactory
{
    const ORM_DOCTRINE = 'doctrine';

    /**
     * Criar uma instância do EntityManager a ser utilizado de acordo com a configuração.
     * @param string $tipo_em Tipo de EntityManager
     * @param array $conexao
     * @param array $config
     * @return DoctrineEntityManager
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    public static function create(string $tipo_em, array $conexao, array $config)
    {
        switch ($tipo_em) {
            case self::ORM_DOCTRINE:
                switch ($config['mapping']) {
                    case 'yaml':
                        $doctrine_config = Setup::createYAMLMetadataConfiguration($config['dir'], false);
                        break;
                    case 'xml':
                        $doctrine_config = Setup::createXMLMetadataConfiguration($config['dir'], false);
                        break;
                    default:
                        $doctrine_config = Setup::createAnnotationMetadataConfiguration($config['dir'], false);
                }

                return DoctrineEntityManager::create($conexao, $doctrine_config);
                break;
            default:
                throw new \Exception('EntityManager não localizado.');
        }
    }
}