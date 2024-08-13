
    <style>
        .card {
            transition: .3s;
        }
        .card:hover{
            cursor: pointer;
            opacity: 0.5;
        }
    </style>

 <div class="row mt-5">
    @foreach ($settings as $setting)
        <div class="col-md-4">
            <div class="card d-flex flex-row align-items-center p-3 rounded-4">
                <i class="bi {{ $setting['icon'] }} {{ $setting['icon_color'] }}" style="font-size: 24px;"></i>
                <div class="ms-3">
                    <h5 class="mb-1">{{ $setting['title'] }}</h5>
                    <p class="mb-0 text-muted">{{ $setting['subtitle'] }}</p>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="font-size: 24px;"></i>
            </div>
        </div>
    @endforeach
</div>
