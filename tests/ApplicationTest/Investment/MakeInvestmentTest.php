<?php
namespace LendInvest\ApplicationTest\Interest;

use LendInvest\Application\Investment\MakeInvestmentInterface;
use LendInvest\Application\Investment\MakeInvestment;
use LendInvest\Application\Investment\MakeInvestmentRequest;

use LendInvest\Domain\Entity;
use LendInvest\Domain\Type;
use LendInvest\Domain\Repository;

/**
 * MakeInvestmentTest
 *
 * @package LendInvest
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class MakeInvestmentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Type\Uuid
     */
    private $id;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Repository\InvestorRepositoryInterface $investors
     */
    private $investors;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Entity\InvestorInterface $investor
     */
    private $investor;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Repository\TrancheRepositoryInterface $tranches
     */
    private $tranches;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Entity\TrancheInterface $tranche
     */
    private $tranche;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Entity\Loan $loan
     */
    private $loan;


    protected function setUp()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository\InvestorRepositoryInterface $investors */
        $this->investors  = $this->getMockForAbstractClass(Repository\InvestorRepositoryInterface::class);

        /** @var \PHPUnit_Framework_MockObject_MockObject|Entity\Investor $investor */
        $this->investor  = $this->getMockForAbstractClass(Entity\InvestorInterface::class);

        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository\TrancheRepositoryInterface $tranches */
        $this->tranches  = $this->getMockForAbstractClass(Repository\TrancheRepositoryInterface::class);

        /** @var \PHPUnit_Framework_MockObject_MockObject|Entity\TrancheInterface $tranche */
        $this->tranche  = $this->getMockForAbstractClass(Entity\TrancheInterface::class);

        /** @var \PHPUnit_Framework_MockObject_MockObject|Entity\LoanInterface $loan */
        $this->loan  = $this->getMockForAbstractClass(Entity\LoanInterface::class);
    }


    /**
     * @test
     * @group application
     */
    public function itCanBeConstructed()
    {
        $service = new MakeInvestment($this->investors, $this->tranches);

        self::assertInstanceOf(MakeInvestmentInterface::class, $service);
    }


    /**
     * @test
     * @group application
     */
    public function itCanMakeAnInvestment()
    {
        $request = new MakeInvestmentRequest('investor', 'tranche', 100);

        $this->investors
             ->expects($this->once())
             ->method('find')
             ->with($request->investor())
             ->willReturn($this->investor)
        ;

        $this->tranches
             ->expects($this->once())
             ->method('find')
             ->with($request->tranche())
             ->willReturn($this->tranche)
        ;

        $service = new MakeInvestment($this->investors, $this->tranches);
        $service($request);
    }


    /**
     * @test
     * @group application
     * @expectedException \Exception
     * @expectedExceptionMessage Investor does not exist
     */
    public function itCanThrowAnExceptionIfInvestorDoesNotExist()
    {
        $request = new MakeInvestmentRequest('investor', 'tranche', 100);

        $this->investors
             ->expects($this->once())
             ->method('find')
             ->with($request->investor())
             ->willReturn(null)
        ;

        (new MakeInvestment($this->investors, $this->tranches))($request);
    }

    /**
     * @test
     * @group application
     * @expectedException \Exception
     * @expectedExceptionMessage Tranche does not exist
     */
    public function itCanThrowAnExceptionIfTrancheDoesNotExist()
    {
        $request = new MakeInvestmentRequest('investor', 'tranche', 100);

        $this->investors
             ->expects($this->once())
             ->method('find')
             ->with($request->investor())
             ->willReturn($this->investor)
        ;

        $this->tranches
             ->expects($this->once())
             ->method('find')
             ->with($request->tranche())
             ->willReturn(null)
        ;

        (new MakeInvestment($this->investors, $this->tranches))($request);
    }
}
