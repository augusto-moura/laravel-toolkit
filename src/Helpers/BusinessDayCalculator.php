<?php
namespace AugustoMoura\LaravelToolkit\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Laravel and Carbon business day calculator, based on the packages andrejsstepanovs/business-days-calculator and SchoppAx/business-days-calculator. 
*/
class BusinessDayCalculator
{
	const MONDAY    = 1;
    const TUESDAY   = 2;
    const WEDNESDAY = 3;
    const THURSDAY  = 4;
    const FRIDAY    = 5;
    const SATURDAY  = 6;
    const SUNDAY    = 7;
    
    const WEEK_DAY_FORMAT = 'N';
    const HOLIDAY_FORMAT  = 'm-d';
    const FREE_DAY_FORMAT = 'Y-m-d';
    
    /** @var Collection<Carbon> */
    private $holidays;
	
    /** @var Collection<Carbon> */
    private $freeDays;

    /** @var Collection<int> */
    private $freeWeekDays;
    
	function __construct()
	{
		$this->holidays = collect([]);
		$this->freeDays = collect([]);
		$this->freeWeekDays = collect([self::SATURDAY, self::SUNDAY]);
	}

    /**
     * @param iterable<Carbon> $holidays Array or collection of holidays that repeats each year. (Only month and date is used to match).
     *
     * @return $this
     */
    public function setHolidays(iterable $holidays) : self
	{
		$holidays = Collection::wrap($holidays);
        $this->holidays = $holidays;
		return $this;
    }

    /**
     * @return Collection<Carbon>
     */
    private function getHolidays() : Collection
	{
        return $this->holidays;
    }
    
    /**
     * @param iterable<Carbon> $freeDays Array or collection of free days that dose not repeat.
     *
     * @return $this
     */
    public function setFreeDays(iterable $freeDays) : self
	{
		$freeDays = Collection::wrap($freeDays);
        $this->freeDays = $freeDays;
		return $this;
    }

    /**
     * @return Collection<Carbon>
     */
    private function getFreeDays() : Collection
	{
        return $this->freeDays;
    }
    
    /**
     * @param iterable<int> $freeWeekDays Array or collection of days of the week which are not business days.
     */
    public function setFreeWeekDays(iterable $freeWeekDays) : self
	{
		$freeWeekDays = Collection::wrap($freeWeekDays);
        $this->freeWeekDays = $freeWeekDays;
		return $this;
    }

    /**
     * @return Collection<int>
     */
    private function getFreeWeekDays() : Collection
	{
        if (count($this->freeWeekDays) >= 7) {
            throw new \InvalidArgumentException('Too many non business days provided');
        }
        return $this->freeWeekDays;
    }

    private function determineSign($x) : int
	{
    	return $x > 0 ? 1 : -1;
    }
    
    public function addBusinessDays(Carbon $date, int $amount) : Carbon
	{
        $dates = $this->getBusinessDays($date, $amount);
        return $dates->last();
    }

    public function getBusinessDays(Carbon $startDate, int $amount) : Collection
	{
        if ($amount === 0 || is_nan($amount)) { return collect([$startDate]); }

        $dates = collect([]);
        $sign = $this->determineSign($amount);
        $absIncrement = abs($amount);
        $newDate = $startDate->copy();
        $iterator = 0;
        while ($iterator < $absIncrement) {
			if($sign > 0){
				$newDate->addDays(1);
			}
			else{
				$newDate->subDays(1);
			}

            if ($this->isBusinessDay($newDate)) {
                $dates->push($newDate->copy());
                $iterator++;
            }
        }
        return $dates;
    }
    
    public function getBusinessDaysBetween(Carbon $startdate, Carbon $enddate) : Collection
	{
        if($startdate > $enddate) {
            throw new \InvalidArgumentException('Startdate must be less or equal to enddate.');
        }

        $dates = collect([]);
        $newdate = $startdate->copy();
        while ($newdate <= $enddate) {
            if ($this->isBusinessDay($newdate)) {
                $dates->push($newdate->copy());
            }
            $newdate->addDays(1);
        }
        return $dates;
    }
    
    public function isBusinessDay(Carbon $date) : bool
	{
        if ($this->isFreeWeekDayDay($date) || $this->isHoliday($date) || $this->isFreeDay($date)) {
            return false;
        }
        
        return true;
    }
    
    public function isFreeWeekDayDay(Carbon $date) : bool
	{
        $currentWeekDay = (int) $date->format(self::WEEK_DAY_FORMAT);
        if ($this->getFreeWeekDays()->contains($currentWeekDay)) {
            return true;
        }
        return false;
    }
    
    public function isHoliday(Carbon $date) : bool
	{
        $holidayFormatValue = $date->format(self::HOLIDAY_FORMAT);
        foreach ($this->getHolidays() as $holiday) {
            if ($holidayFormatValue == $holiday->format(self::HOLIDAY_FORMAT)) {
                return true;
            }
        }
        return false;
    }
    
    public function isFreeDay(Carbon $date) : bool
	{
        $freeDayFormatValue = $date->format(self::FREE_DAY_FORMAT);
        foreach ($this->getFreeDays() as $freeDay) {
            if ($freeDayFormatValue == $freeDay->format(self::FREE_DAY_FORMAT)) {
                return true;
            }
        }
        return false;
    }
}