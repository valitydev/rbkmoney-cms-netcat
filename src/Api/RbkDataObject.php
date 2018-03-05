<?php

namespace src\Api;

abstract class RbkDataObject
{

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset(string $name): bool {
        return property_exists(get_called_class(), $name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name) {
        if ($this->__isset($name)) {
            return $this->$name;
        }
    }

    /**
     * Метод объявлен только для того, чтоб
     * запретить динамически создавать поля объекта
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set(string $name, $value) {
        // Реализация не предполагается
    }

}
