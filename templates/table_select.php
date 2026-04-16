<form method="get" class="row g-3 align-items-end mt-3">
    <input type="hidden" name="db" value="<?= htmlspecialchars($selectedDb) ?>">
    <div class="col-md-6">
        <label class="form-label fw-bold">Таблица</label>
        <select name="table" class="form-select" onchange="this.form.submit()">
            <option value="">-- Выберите таблицу --</option>
            <?php foreach ($tables as $tbl): ?>
                <option value="<?= htmlspecialchars($tbl) ?>" <?= ($selectedTable === $tbl) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tbl) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</form>