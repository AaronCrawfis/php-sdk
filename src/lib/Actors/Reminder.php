<?php

namespace Dapr\Actors;

use Dapr\Formats;
use DateInterval;

class Reminder
{
    /**
     * Reminder constructor.
     *
     * @param string $name The name of the reminder
     * @param DateInterval $due_time The due time of the reminder
     * @param array|null $data The data to pass to the reminder
     * @param DateInterval|null $period The period of the reminder
     */
    public function __construct(
        public string $name,
        public DateInterval $due_time,
        public ?array $data,
        public ?DateInterval $period = null
    ) {
    }

    /**
     * @param string $name The name of the reminder
     * @param array|null $api
     *
     * @return Reminder|null
     */
    public static function from_api(
        string $name,
        ?array $api
    ): ?Reminder {
        if ($api === null) {
            return null;
        }

        return new Reminder(
            $name,
            Formats::from_dapr_interval($api['dueTime']),
            json_decode($api['data'], true) ?? [],
            Formats::from_dapr_interval($api['period'])
        );
    }

    public function to_array(): array
    {
        return [
            'dueTime' => Formats::normalize_interval($this->due_time),
            'period'  => Formats::normalize_interval($this->period),
            'data'    => json_encode($this->data),
        ];
    }
}
