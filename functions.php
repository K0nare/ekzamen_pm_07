<?php
// functions.php - все вспомогательные функции

function getDatabases($pdo) {
    // Получаем все базы данных, исключая системные
    $stmt = $pdo->query("SHOW DATABASES");
    $allDbs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Исключаем системные БД
    $systemDbs = ['information_schema', 'mysql', 'performance_schema', 'phpmyadmin', 'sys'];
    $userDbs = array_diff($allDbs, $systemDbs);
    
    return $userDbs;
}

function getTables($pdo, $db) {
    $pdo->exec("USE `$db`");
    $stmt = $pdo->query("SHOW TABLES");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getColumnsInfo($pdo, $table) {
    $stmt = $pdo->prepare("DESCRIBE `$table`");
    $stmt->execute();
    $cols = [];
    $primaryKey = null;
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        $type = $col['Type'];
        $enumValues = [];
        if (preg_match('/^enum\((.*)\)$/i', $type, $matches)) {
            $enumValues = str_getcsv($matches[1], ',', "'");
        }
        $cols[] = [
            'name' => $col['Field'],
            'type' => $type,
            'null' => $col['Null'] === 'YES',
            'key' => $col['Key'],
            'default' => $col['Default'],
            'enum' => $enumValues
        ];
        if ($col['Key'] === 'PRI') $primaryKey = $col['Field'];
    }
    return [$cols, $primaryKey];
}

function getForeignKeys($pdo, $table, $db) {
    $sql = "SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$db, $table]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDisplayValue($pdo, $table, $id, $keyColumn) {
    if (!$id) return '';
    $stmt = $pdo->prepare("DESCRIBE `$table`");
    $stmt->execute();
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $displayCol = $keyColumn;
    foreach ($cols as $col) {
        $name = $col['Field'];
        if (in_array($name, ['name', 'title', 'full_name', 'login', 'company_name', 'description'])) {
            $displayCol = $name;
            break;
        }
    }
    $stmt2 = $pdo->prepare("SELECT `$displayCol` FROM `$table` WHERE `$keyColumn` = ?");
    $stmt2->execute([$id]);
    $row = $stmt2->fetch(PDO::FETCH_ASSOC);
    return $row ? $row[$displayCol] : $id;
}

function getAllRows($pdo, $table) {
    $stmt = $pdo->query("SELECT * FROM `$table`");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRowData($pdo, $table, $id, $primaryKey) {
    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE `$primaryKey` = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>