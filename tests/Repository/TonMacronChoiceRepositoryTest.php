<?php

namespace Tests\AppBundle\Repository;

use AppBundle\Entity\TonMacronChoice;
use AppBundle\Repository\TonMacronChoiceRepository;
use Tests\AppBundle\Controller\ControllerTestTrait;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * @group functional
 */
class TonMacronChoiceRepositoryTest extends WebTestCase
{
    /**
     * @var TonMacronChoiceRepository
     */
    private $repository;

    use ControllerTestTrait;

    public function testGetMailIntroductionAndConclusion()
    {
        $introduction = $this->repository->findMailIntroduction();

        $this->assertInstanceOf(TonMacronChoice::class, $introduction);
        $this->assertSame('8a2fdb59-357f-4e74-9aeb-c2b064d31064', $introduction->getUuid()->toString());

        $conclusion = $this->repository->findMailConclusion();

        $this->assertInstanceOf(TonMacronChoice::class, $conclusion);
        $this->assertSame('31276b63-a4f3-4994-aca8-ed4ca78c173e', $conclusion->getUuid()->toString());
    }

    protected function setUp()
    {
        parent::setUp();

        $this->container = $this->getContainer();
        $this->repository = $this->getTonMacronChoiceRepository();
    }

    protected function tearDown()
    {
        $this->kill();

        $this->repository = null;
        $this->container = null;

        parent::tearDown();
    }
}
