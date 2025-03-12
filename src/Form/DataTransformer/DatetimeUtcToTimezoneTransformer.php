<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
class DatetimeUtcToTimezoneTransformer implements DataTransformerInterface
{
    private string $timezone;

    public function __construct(string $timezone)
    {
        // exit;
        $this->timezone = $timezone;
    }

    public function transform($dateTimeUtc): \DateTimeImmutable
    {
        if($dateTimeUtc === null){
            return new \DateTimeImmutable( 'now', new \DateTimeZone($this->timezone));
        }
        $dateTime = $dateTimeUtc->setTimezone(new \DateTimeZone($this->timezone));
        return $dateTime;
    }

    public function reverseTransform($dateTime): \DateTimeImmutable
    {
        $dateTimeString = $dateTime->format('Y-m-d H:i:s');
        $originalTimezone = new \DateTimeZone($this->timezone); 
        $originalDateTime = new \DateTimeImmutable($dateTimeString, $originalTimezone);
        $dateTimeUtc = $originalDateTime->setTimezone(new \DateTimeZone('UTC'));
        return $dateTimeUtc;
    }
}