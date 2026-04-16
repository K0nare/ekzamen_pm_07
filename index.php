<?php
require_once 'config.php';
require_once 'functions.php';

$selectedDb = $_GET['db'] ?? '';
$selectedTable = $_GET['table'] ?? '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

$databases = getDatabases($pdo);

if ($selectedDb && in_array($selectedDb, $databases)) {
    $pdo->exec("USE `$selectedDb`");
    $tables = getTables($pdo, $selectedDb);
} else {
    $tables = [];
}

// Обработка POST (добавление / редактирование)
if ($selectedDb && $selectedTable && $_SERVER['REQUEST_METHOD'] === 'POST') {
    [$colsInfo, $primaryKey] = getColumnsInfo($pdo, $selectedTable);
    if ($action === 'add') {
        $setParts = []; $values = [];
        foreach ($colsInfo as $col) {
            $name = $col['name'];
            if ($name === $primaryKey) continue;
            if (isset($_POST[$name]) && $_POST[$name] !== '') {
                $setParts[] = "`$name` = ?";
                $values[] = $_POST[$name];
            }
        }
        if ($setParts) {
            $sql = "INSERT INTO `$selectedTable` SET " . implode(',', $setParts);
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
        }
        header("Location: ?db=" . urlencode($selectedDb) . "&table=" . urlencode($selectedTable));
        exit;
    }
    elseif ($action === 'edit' && $id) {
        $setParts = []; $values = [];
        foreach ($colsInfo as $col) {
            $name = $col['name'];
            if ($name === $primaryKey) continue;
            if (isset($_POST[$name])) {
                $setParts[] = "`$name` = ?";
                $values[] = $_POST[$name];
            }
        }
        if ($setParts) {
            $values[] = $id;
            $sql = "UPDATE `$selectedTable` SET " . implode(',', $setParts) . " WHERE `$primaryKey` = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
        }
        header("Location: ?db=" . urlencode($selectedDb) . "&table=" . urlencode($selectedTable));
        exit;
    }
}

// Удаление
if ($selectedDb && $selectedTable && $action === 'delete' && $id) {
    [$colsInfo, $primaryKey] = getColumnsInfo($pdo, $selectedTable);
    $stmt = $pdo->prepare("DELETE FROM `$selectedTable` WHERE `$primaryKey` = ?");
    $stmt->execute([$id]);
    header("Location: ?db=" . urlencode($selectedDb) . "&table=" . urlencode($selectedTable));
    exit;
}

// Получение данных для отображения
$rows = [];
$colsInfo = [];
$primaryKey = null;
$foreignKeys = [];
$editRow = null;

if ($selectedDb && $selectedTable) {
    [$colsInfo, $primaryKey] = getColumnsInfo($pdo, $selectedTable);
    $foreignKeys = getForeignKeys($pdo, $selectedTable, $selectedDb);
    $rows = getAllRows($pdo, $selectedTable);
    
    if ($action === 'edit' && $id) {
        $editRow = getRowData($pdo, $selectedTable, $id, $primaryKey);
    }
}

// Подключаем шапку
include 'templates/header.php';
?>

<!-- Выбор БД и таблицы -->
<div class="card mb-4">
    <div class="card-body">
        <?php include 'templates/db_select.php'; ?>
        <?php if ($selectedDb && $tables): ?>
            <?php include 'templates/table_select.php'; ?>
        <?php endif; ?>
    </div>
</div>

<?php if ($selectedDb && $selectedTable): ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-table me-2"></i><?= htmlspecialchars($selectedTable) ?></h2>
        <a href="?db=<?= urlencode($selectedDb) ?>&table=<?= urlencode($selectedTable) ?>&action=add" class="btn btn-success">
            <i class="fas fa-plus"></i> Добавить запись
        </a>
    </div>

    <?php include 'templates/table.php'; ?>

    <?php if ($action === 'add' || ($action === 'edit' && $editRow)): ?>
        <?php include 'templates/form.php'; ?>
    <?php endif; ?>
<?php endif; ?>

<?php include 'templates/footer.php'; ?>