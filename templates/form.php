<div class="card mt-4 shadow-sm">
    <div class="card-header bg-primary text-white">
        <i class="fas <?= $action === 'add' ? 'fa-plus-circle' : 'fa-pen' ?> me-2"></i>
        <?= $action === 'add' ? 'Добавление записи' : 'Редактирование записи' ?>
    </div>
    <div class="card-body">
        <form method="post">
            <?php foreach ($colsInfo as $col): 
                $name = $col['name'];
                if ($name === $primaryKey) continue;
                $value = $editRow ? ($editRow[$name] ?? '') : '';
                $isFk = false;
                $fkTable = $fkColumn = '';
                foreach ($foreignKeys as $fk) {
                    if ($fk['COLUMN_NAME'] === $name) {
                        $isFk = true;
                        $fkTable = $fk['REFERENCED_TABLE_NAME'];
                        $fkColumn = $fk['REFERENCED_COLUMN_NAME'];
                        break;
                    }
                }
            ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold"><?= htmlspecialchars($name) ?></label>
                    <?php if ($isFk && $fkTable): ?>
                        <select name="<?= $name ?>" class="form-select">
                            <option value="">-- Не выбрано --</option>
                            <?php
                            $fkStmt = $pdo->query("SELECT * FROM `$fkTable`");
                            $fkRows = $fkStmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($fkRows as $opt) {
                                $optId = $opt[$fkColumn];
                                $selected = ($value == $optId) ? 'selected' : '';
                                $display = $optId;
                                foreach (['name', 'title', 'full_name', 'login', 'company_name', 'description'] as $candidate) {
                                    if (isset($opt[$candidate])) { $display = $opt[$candidate]; break; }
                                }
                                echo "<option value='$optId' $selected>" . htmlspecialchars($display) . "</option>";
                            }
                            ?>
                        </select>
                    <?php elseif (!empty($col['enum'])): ?>
                        <select name="<?= $name ?>" class="form-select">
                            <option value="">-- Выберите --</option>
                            <?php foreach ($col['enum'] as $enumVal): ?>
                                <option value="<?= htmlspecialchars($enumVal) ?>" <?= ($value == $enumVal) ? 'selected' : '' ?>><?= htmlspecialchars($enumVal) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input type="text" name="<?= $name ?>" class="form-control" value="<?= htmlspecialchars($value) ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i> <?= $action === 'add' ? 'Добавить' : 'Сохранить' ?>
            </button>
            <a href="?db=<?= urlencode($selectedDb) ?>&table=<?= urlencode($selectedTable) ?>" class="btn btn-secondary px-4">
                <i class="fas fa-times me-1"></i> Отмена
            </a>
        </form>
    </div>
</div>