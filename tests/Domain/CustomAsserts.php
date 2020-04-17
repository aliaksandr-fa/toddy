<?php declare(strict_types=1);


namespace Toddy\Tests\Domain;


trait CustomAsserts
{
    public function assertContainsInstancesOf(string $className, iterable $haystack, string $message = '')
    {

        $found = false;

        foreach ($haystack as $item) {
            if (get_class($item) === $className) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, $message);
    }
}