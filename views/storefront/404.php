<?php
declare(strict_types=1);

use App\Core\View;
?>
<div class="container py-5 text-center my-5">
  <div class="max-w-700 mx-auto">
    <div class="display-1 fw-extrabold text-primary mb-3">404</div>
    <h1 class="h2 fw-bold text-dark mb-3">Door Access Denied — Page Not Found</h1>
    <p class="text-muted fs-6 mb-4">
      The smart lock product, category, or resource you were searching for cannot be located or has been relocated.
    </p>

    <!-- Search bar -->
    <div class="row justify-content-center mb-4">
      <div class="col-md-8 col-lg-6">
        <form action="<?= url('shop') ?>" method="GET" class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search digital door locks...">
          <button type="submit" class="btn btn-dark"><i class="bi bi-search"></i></button>
        </form>
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-center flex-wrap gap-3">
      <a href="<?= url('/') ?>" class="btn btn-hiq-primary">
        <i class="bi bi-house me-1"></i> Return Home
      </a>
      <a href="<?= url('shop') ?>" class="btn btn-hiq-outline">
        <i class="bi bi-grid me-1"></i> Shop All Locks
      </a>
    </div>
  </div>
</div>

