<?php

class Database {

    private static $wasInitialized = FALSE;

    private static $connection;

    private static function initialize($dbFile) {
        if (self::$wasInitialized === TRUE) return;
        self::$wasInitialized = TRUE;
        self::$connection = new SQLite3($dbFile, SQLITE3_OPEN_READONLY);
    }

    public static function getConnection($dbFile) {
        self::initialize($dbFile);
        return self::$connection;
    }
}
