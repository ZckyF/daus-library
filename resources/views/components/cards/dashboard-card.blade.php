<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div class="left">
        <h5 class="card-title">{{ $slot }}</h5>
        <h3 class="card-text">{{ $count }}</h3>
      </div>
      <div class="right">
        <div class="icon-circle bg-primary text-white d-flex justify-content-center align-items-center">
          <i class="bi bi-{{ $icon }} fs-3"></i>
        </div>
      </div>
    </div>
  </div>