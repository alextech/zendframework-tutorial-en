<?php

namespace ZFT;

use Zend\Db\Sql\Platform\SqlServer\SelectDecorator;

class JsonSelect extends SelectDecorator {
    public function __construct($table = null) {
        $this->specifications['statementEnd'] = "%1\$s\nFOR JSON AUTO";

        parent::__construct($table);
    }
}
