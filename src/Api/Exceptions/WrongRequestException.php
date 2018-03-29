<?php

namespace src\Api\Exceptions;

use src\Exceptions\RBKMoneyException;

/**
 * Выбрасывается в случае передачи невалидных данных в клиент
 */
class WrongRequestException extends RBKMoneyException
{

}
