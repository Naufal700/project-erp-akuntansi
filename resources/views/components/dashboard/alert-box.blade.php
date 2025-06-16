@props(['icon', 'title', 'description', 'type'])

<div class="alert alert-{{ $type }} d-flex align-items-center shadow-sm" role="alert" data-aos="fade-left">
    <i class="fas fa-{{ $icon }} me-2"></i>
    <div>
        <strong>{{ $title }}</strong><br>
        <small>{{ $description }}</small>
    </div>
</div>
