<?php
namespace Tests;

use AugustoMoura\LaravelToolkit\Helpers\BusinessDayCalculator as Calculator;
use Orchestra\Testbench\TestCase;
use Carbon\Carbon;

class BusinessDayCalculatorTest extends TestCase
{
    /** @var Calculator */
    private $calculator;

    public function setUp()
    {
        $this->calculator = new Calculator();
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return include __DIR__ . '/fixtures/business_days.php';
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string      $message
     * @param Carbon   $startDate
     * @param int         $howManyDays
     * @param Carbon   $expected
     * @param int[]       $nonBusinessDays
     * @param Carbon[] $freeDays
     * @param Carbon[] $holidays
     */
    public function testReturnsExpected(
        $message,
        Carbon $startDate,
        $howManyDays,
        Carbon $expected,
        array $nonBusinessDays = array(),
        array $freeDays = array(),
        array $holidays = array()
    ) {
		$this->calculator->setFreeDays($freeDays);
        $this->calculator->setHolidays($holidays);
        $this->calculator->setFreeWeekDays($nonBusinessDays);

        $response = $this->calculator->addBusinessDays($startDate, $howManyDays);

        $this->assertEquals($response, $expected, $message);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTooManyBusinessDaysException()
    {
        $nonBusinessDays = [
            Calculator::MONDAY,
            Calculator::TUESDAY,
            Calculator::WEDNESDAY,
            Calculator::THURSDAY,
            Calculator::FRIDAY,
            Calculator::SATURDAY,
            Calculator::SUNDAY
        ];

        $this->calculator->setFreeWeekDays($nonBusinessDays);
        $this->calculator->addBusinessDays($date, 1);
    }

    public function testThatPassedParameterIsNotChangedByReferenceInSut()
    {
        $date = new Carbon('2000-01-01');

        $responseDate = $this->calculator->addBusinessDays($date, 1);

        $this->assertNotEquals($date, $responseDate);
    }
}