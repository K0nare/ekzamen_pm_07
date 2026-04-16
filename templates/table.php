<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <?php foreach ($colsInfo as $col): ?>
                    <th><?= htmlspecialchars($col['name']) ?></th>
                <?php endforeach; ?>
                <th style="width: 100px">Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <?php foreach ($colsInfo as $col): 
                        $val = $row[$col['name']];
                        $isFk = false;
                        foreach ($foreignKeys as $fk) {
                            if ($fk['COLUMN_NAME'] === $col['name']) {
                                $isFk = true;
                                $refTable = $fk['REFERENCED_TABLE_NAME'];
                                $refCol = $fk['REFERENCED_COLUMN_NAME'];
                                echo "<td>" . htmlspecialchars(getDisplayValue($pdo, $refTable, $val, $refCol)) . "</td>";
                                break;
                            }
                        }
                        if (!$isFk) echo "<td>" . htmlspecialchars($val ?? '') . "</td>";
                    endforeach; ?>
                    <td class="text-nowrap">
                        <a href="?db=<?= urlencode($selectedDb) ?>&table=<?= urlencode($selectedTable) ?>&action=edit&id=<?= $row[$primaryKey] ?>" class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="?db=<?= urlencode($selectedDb) ?>&table=<?= urlencode($selectedTable) ?>&action=delete&id=<?= $row[$primaryKey] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить запись?')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>