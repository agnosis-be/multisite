<?php
// This file: /app/models/Table.class.php (UTF-8/LF/4 SP)
// By: agnosis.be
// Repo: multisite
// Version: 1.0

/**
 * OR Mapper for a database table.
 *
 * Properties are automatically reflected
 *
 * Usage:
 *   $f3 = Base::instance();
 *   $tblSite = new Table($f3->db);
 *
 * @see https://fatfreeframework.com/3.7/sql-mapper
 * @see https://fatfreeframework.com/3.7/databases
 */
class Table extends DB\SQL\Mapper {
    function __construct(DB\SQL $db, $table) {
        parent::__construct($db, $table);
    }

    /**
     * Generate parameter string from Array,
     * e.g.: [1,2,3] => '?,?,?'
     *
     * Used for parameterized queries
     */
    static function toParams(array $arr): string {
        return join(',', array_fill(0, count($arr), '?'));
    }
}
?>
