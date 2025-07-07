<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-map me-2"></i>
                <?= $localization->get('tours') ?> - <?= $localization->get('overview') ?>
            </h1>
            <a href="/tours/add" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                <?= $localization->get('add') ?> <?= $localization->get('tour') ?>
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title"><?= $localization->get('total_tours') ?></h6>
                        <h3 class="mb-0"><?= $pagination['total_records'] ?? 0 ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-map fs-1"></i>
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
                        <h6 class="card-title"><?= $localization->get('completed_tours') ?></h6>
                        <h3 class="mb-0"><?= $stats['completed_count'] ?? 0 ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-1"></i>
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
                        <h6 class="card-title"><?= $localization->get('total_distance') ?></h6>
                        <h3 class="mb-0"><?= number_format($stats['total_distance'] ?? 0, 1) ?> km</h3>
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
                        <h6 class="card-title"><?= $localization->get('total_earnings') ?></h6>
                        <h3 class="mb-0">€<?= number_format($stats['total_earnings'] ?? 0, 2) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-euro fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tours Table -->
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0"><?= $localization->get('tours') ?></h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="<?= $localization->get('search_tours') ?>">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($tours)): ?>
            <div class="text-center py-5">
                <i class="bi bi-map fs-1 text-muted mb-3"></i>
                <h5 class="text-muted"><?= $localization->get('no_tours') ?></h5>
                <p class="text-muted"><?= $localization->get('no_tours_description') ?></p>
                <a href="/tours/add" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    <?= $localization->get('add_first_tour') ?>
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><?= $localization->get('tour_name') ?></th>
                            <th><?= $localization->get('date') ?></th>
                            <th><?= $localization->get('duration') ?></th>
                            <th><?= $localization->get('distance') ?></th>
                            <th><?= $localization->get('amount') ?></th>
                            <th><?= $localization->get('status') ?></th>
                            <th><?= $localization->get('actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tours as $tour): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($tour['tour_name']) ?></strong>
                                </td>
                                <td><?= date('d.m.Y', strtotime($tour['tour_date'])) ?></td>
                                <td><?= $tour['tour_duration'] ? number_format($tour['tour_duration'], 1) . 'h' : '-' ?></td>
                                <td><?= $tour['tour_distance'] ? number_format($tour['tour_distance'], 1) . ' km' : '-' ?></td>
                                <td>
                                    <strong>€<?= number_format($tour['tour_total'], 2) ?></strong>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = match($tour['status']) {
                                        'pending' => 'bg-warning',
                                        'approved' => 'bg-info',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                    $statusText = $localization->get('status_' . $tour['status']);
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="/tours/view/<?= $tour['id'] ?>" 
                                           class="btn btn-outline-primary" 
                                           title="<?= $localization->get('view') ?>">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/tours/edit/<?= $tour['id'] ?>" 
                                           class="btn btn-outline-secondary" 
                                           title="<?= $localization->get('edit') ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                title="<?= $localization->get('delete') ?>"
                                                onclick="deleteTour(<?= $tour['id'] ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['total'] > 1): ?>
                <nav aria-label="<?= $localization->get('pagination') ?>">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagination['current'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pagination['current'] - 1 ?>">
                                    <?= $localization->get('previous') ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total']; $i++): ?>
                            <li class="page-item <?= $i === $pagination['current'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['current'] < $pagination['total']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pagination['current'] + 1 ?>">
                                    <?= $localization->get('next') ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function deleteTour(id) {
    if (confirm('<?= $localization->get('confirm_delete_tour') ?>')) {
        fetch(`/tours/delete/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '<?= $localization->get('error_delete') ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?= $localization->get('error_delete') ?>');
        });
    }
}

// Search functionality
document.getElementById('searchBtn').addEventListener('click', function() {
    const query = document.getElementById('searchInput').value;
    if (query.trim()) {
        window.location.href = `/tours/search?q=${encodeURIComponent(query)}`;
    }
});

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('searchBtn').click();
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?> 