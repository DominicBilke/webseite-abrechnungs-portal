<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    <?= $localization->get('tours') ?> - <?= $localization->get('reports') ?>
                </h4>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="from_date" class="form-label"><?= $localization->get('report_from_date') ?></label>
                        <input type="date" class="form-control" id="from_date" name="from_date" 
                               value="<?= htmlspecialchars($filters['from_date']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="to_date" class="form-label"><?= $localization->get('report_to_date') ?></label>
                        <input type="date" class="form-control" id="to_date" name="to_date" 
                               value="<?= htmlspecialchars($filters['to_date']) ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i>
                            <?= $localization->get('filter') ?>
                        </button>
                        <a href="/tours/reports" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            <?= $localization->get('reset') ?>
                        </a>
                    </div>
                </form>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title"><?= $localization->get('total_tours') ?></h6>
                                        <h3 class="mb-0"><?= $stats['total_tours'] ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-car-front fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title"><?= $localization->get('total_distance') ?> (km)</h6>
                                        <h3 class="mb-0"><?= number_format($stats['total_distance'], 1) ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-speedometer2 fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title"><?= $localization->get('total_duration') ?> (<?= $localization->get('hours') ?>)</h6>
                                        <h3 class="mb-0"><?= number_format($stats['total_duration'], 1) ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-clock-history fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title"><?= $localization->get('total_earnings') ?></h6>
                                        <h3 class="mb-0">€<?= number_format($stats['total_earnings'], 2) ?></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-cash-coin fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tours Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th><?= $localization->get('tour_name') ?></th>
                                <th><?= $localization->get('tour_date') ?></th>
                                <th><?= $localization->get('tour_duration') ?></th>
                                <th><?= $localization->get('tour_distance') ?></th>
                                <th><?= $localization->get('tour_rate') ?></th>
                                <th><?= $localization->get('total') ?></th>
                                <th><?= $localization->get('status') ?></th>
                                <th><?= $localization->get('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tours)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <?= $localization->get('no_tours_found') ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tours as $tour): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($tour['tour_name']) ?></strong>
                                            <?php if ($tour['tour_description']): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars(substr($tour['tour_description'], 0, 50)) ?>...</small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d.m.Y', strtotime($tour['tour_date'])) ?></td>
                                        <td><?= number_format($tour['tour_duration'], 1) ?> <?= $localization->get('hours') ?></td>
                                        <td><?= number_format($tour['tour_distance'], 1) ?> km</td>
                                        <td>€<?= number_format($tour['tour_rate'], 2) ?></td>
                                        <td><strong>€<?= number_format($tour['tour_total'], 2) ?></strong></td>
                                        <td>
                                            <?php
                                            $statusClass = match($tour['status']) {
                                                'completed' => 'success',
                                                'approved' => 'info',
                                                'pending' => 'warning',
                                                'cancelled' => 'danger',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-<?= $statusClass ?>">
                                                <?= $localization->get('status_' . $tour['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="/tours/view/<?= $tour['id'] ?>" class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="/tours/edit/<?= $tour['id'] ?>" class="btn btn-outline-secondary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Export Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <a href="/tours/overview" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            <?= $localization->get('back_to_overview') ?>
                        </a>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" onclick="exportToPDF()">
                            <i class="bi bi-file-pdf me-1"></i>
                            <?= $localization->get('export_pdf') ?>
                        </button>
                        <button type="button" class="btn btn-info" onclick="exportToExcel()">
                            <i class="bi bi-file-excel me-1"></i>
                            <?= $localization->get('export_excel') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportToPDF() {
    // Implementation for PDF export
    alert('PDF export functionality will be implemented here');
}

function exportToExcel() {
    // Implementation for Excel export
    alert('Excel export functionality will be implemented here');
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 