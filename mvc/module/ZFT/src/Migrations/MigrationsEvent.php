<?php

namespace ZFT\Migrations;

use Zend\EventManager\Event;

class MigrationsEvent extends Event {
    const MIGRATIONS_START = 'migrations.start';
    const UPDATE_START = 'migrations.update.start';
    const UPDATE_FINISH = 'migrations.update.finish';
    const MIGRATIONS_FINISH = 'migrations.finish';

    const MIGRATIONS_FAILED = 'migrations.failed';
    const UPDATE_FAILED = 'migrations.update.failed';
}
