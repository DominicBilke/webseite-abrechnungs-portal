<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-eye me-2"></i>
                    <?= $localization->get('view') ?> <?= $localization->get('tour') ?>
                </h4>
                <div class="btn-group">
                    <a href="/tours/edit/<?= $tour['id'] ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>
                        <?= $localization->get('edit') ?>
                    </a>
                    <a href="/tours/overview" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>
                        <?= $localization->get('back') ?>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3"><?= $localization->get('tour_information') ?></h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('tour_name') ?></label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($tour['tour_name']) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('tour_description') ?></label>
                            <p class="form-control-plaintext">
                                <?= $tour['tour_description'] ? nl2br(htmlspecialchars($tour['tour_description'])) : $localization->get('no_description') ?>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('tour_date') ?></label>
                            <p class="form-control-plaintext"><?= date('d.m.Y', strtotime($tour['tour_date'])) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('tour_route') ?></label>
                            <p class="form-control-plaintext">
                                <?= $tour['tour_route'] ? htmlspecialchars($tour['tour_route']) : $localization->get('no_route') ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3"><?= $localization->get('tour_details') ?></h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('status') ?></label>
                            <p class="form-control-plaintext">
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
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('tour_start') ?></label>
                            <p class="form-control-plaintext">
                                <?= $tour['tour_start'] ? date('H:i', strtotime($tour['tour_start'])) : $localization->get('not_specified') ?>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('tour_end') ?></label>
                            <p class="form-control-plaintext">
                                <?= $tour['tour_end'] ? date('H:i', strtotime($tour['tour_end'])) : $localization->get('not_specified') ?>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('tour_duration') ?></label>
                            <p class="form-control-plaintext"><?= number_format($tour['tour_duration'], 1) ?> <?= $localization->get('hours') ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('tour_distance') ?></label>
                            <p class="form-control-plaintext"><?= number_format($tour['tour_distance'], 1) ?> km</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('tour_rate') ?></label>
                            <p class="form-control-plaintext">€<?= number_format($tour['tour_rate'], 2) ?> / <?= $localization->get('hour') ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('total') ?></label>
                            <p class="form-control-plaintext">
                                <strong>€<?= number_format($tour['tour_total'], 2) ?></strong>
                            </p>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3"><?= $localization->get('vehicle_information') ?></h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('tour_vehicle') ?></label>
                            <p class="form-control-plaintext">
                                <?= $tour['tour_vehicle'] ? htmlspecialchars($tour['tour_vehicle']) : $localization->get('not_specified') ?>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('tour_driver') ?></label>
                            <p class="form-control-plaintext">
                                <?= $tour['tour_driver'] ? htmlspecialchars($tour['tour_driver']) : $localization->get('not_specified') ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3"><?= $localization->get('timestamps') ?></h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('created') ?></label>
                            <p class="form-control-plaintext"><?= date('d.m.Y H:i', strtotime($tour['created_at'])) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold"><?= $localization->get('updated') ?></label>
                            <p class="form-control-plaintext"><?= date('d.m.Y H:i', strtotime($tour['updated_at'])) ?></p>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <div></div>
                    
                    <div class="btn-group">
                        <a href="/tours/edit/<?= $tour['id'] ?>" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i>
                            <?= $localization->get('edit') ?>
                        </a>
                        <a href="/tours/overview" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            <?= $localization->get('back_to_overview') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?> 