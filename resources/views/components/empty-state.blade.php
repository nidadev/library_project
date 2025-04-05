@props([
    'title' => 'Nothing to show here',
    'message' => 'No data available at the moment.',
    'image' => 'empty.svg',
    'buttonText' => null,
    'buttonUrl' => null,
    'modalTarget' => null,
])
<div class="text-center py-5">
    <img src="{{ asset($image) }}" alt="{{ $title }}" width="150">
    <h4 class="mt-3">{{ $title }}</h4>
    <p>{{ $message }}</p>

    @if ($buttonText)
        @if ($modalTarget)
            <!-- Open Bootstrap Modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="{{ $modalTarget }}">
                <i class="fas fa-plus"></i> {{ $buttonText }}
            </button>
        @elseif ($buttonUrl)
            <!-- Redirect to URL -->
            <a href="{{ $buttonUrl }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ $buttonText }}
            </a>
        @endif
    @endif
</div>
