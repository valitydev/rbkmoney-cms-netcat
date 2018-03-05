<?php

namespace src\Api;

/**
 * Метаданные, которые необходимо связать с инвойсом
 */
class Metadata
{

    /**
     * @var array
     */
    public $metadata;

    /**
     * @param array $metadata
     */
    public function __construct(array $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->metadata;
    }

}
