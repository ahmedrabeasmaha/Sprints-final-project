<div class="text-primary mr-2">
    @foreach ([1, 2, 3, 4, 5] as $i)
    @if ($i <= $rating)
        <small class="fas fa-star"></small>
    @elseif ($i <= $rating + 0.5)
        <small class="fas fa-star-half-alt"></small>
    @else
        <small class="far fa-star"></small>
    @endif
    @endforeach
</div>
