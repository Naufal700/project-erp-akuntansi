@props(['color', 'icon', 'label', 'value'])

<div class="col-lg-3 col-md-6" data-aos="zoom-in">
    <div class="card shadow-sm text-white {{ $color }} hover-scale">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0">{{ $value }}</h5>
                <small>{{ $label }}</small>
            </div>
            <i class="fas fa-{{ $icon }} fa-2x"></i>
        </div>
    </div>
</div>
