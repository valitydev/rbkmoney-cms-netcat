<?php

namespace src\Api\Interfaces;

interface PostRequestInterface
{

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return string
     */
    public function getUrl(): string;

}
