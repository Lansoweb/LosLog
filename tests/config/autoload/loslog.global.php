<?php
$conf = [
    /**
     * Default log directory
     */
    'log_dir' => 'data/logs',

    /**
     * Use EntityLogger
     */
    //'use_entity_logger' => false,

    /**
     * Log filename for the EntityLogger
     */
    //'entity_logger_file' => 'entity.log',

    /**
     * Use SqlLogger
     */
    //'use_sql_logger' => false,

    /**
     * Log filename for the SqlLogger
     */
    //'sql_logger_file' => 'sql.log',

    /**
     * Use AppLogger
     */
    //'use_app_logger' => false,

    /**
     * Log filename for the AppLogger
     */
    //'app_logger_file' => 'error.log',

    /**
     * Log filename for the DevLogger
     */
    //'dev_logger_file' => 'dev.log'
];

return ['loslog' => $conf];
