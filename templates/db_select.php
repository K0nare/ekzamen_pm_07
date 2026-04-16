<form method="get" class="row g-3 align-items-end">
    <div class="col-md-6">
        <label class="form-label fw-bold">База данных</label>
        <select name="db" class="form-select" onchange="this.form.submit()">
            <option value="">-- Выберите БД --</option>
            <?php foreach ($databases as $db): ?>
                <option value="<?= htmlspecialchars($db) ?>" <?= ($selectedDb === $db) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($db) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</form>