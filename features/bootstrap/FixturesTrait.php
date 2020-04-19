<?php declare(strict_types=1);

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;
use Toddy\Infrastructure\Persistence\Doctrine\Fixtures\TaskFixtures;
use Toddy\Infrastructure\Persistence\Doctrine\Fixtures\UserFixtures;

/**
 * Trait FixturesTrait
 * @author Faley Aliaksandr
 */
trait FixturesTrait
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @BeforeScenario
     */
    public function loadDataFixtures(BeforeScenarioScope $scope)
    {
        $container = $this->kernel->getContainer();

        $entityManager = $container->get('doctrine.orm.default_entity_manager');

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();

        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);


        $loader = new Loader();
        $loader->addFixture(new UserFixtures());
        $loader->addFixture(new TaskFixtures());

        $purger = new ORMPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);

        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }
}