@props(['id', 'title', 'icon', 'color', 'height' => '150px'])

<div class="col-lg-6" data-aos="fade-up">
    <div class="card glass-card shadow-sm">
        <div class="card-header bg-transparent border-0">
            <h6 class="mb-0 text-{{ $color }}"><i class="fas fa-{{ $icon }}"></i> {{ $title }}</h6>
        </div>
        <div class="card-body">
            <canvas id="{{ $id }}" style="max-height: {{ $height }}"></canvas>
        </div>
    </div>
</div>
