<div>
    @if ($announcements->count() > 0)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <h4 class="font-bold mb-2">Duyurular</h4>
            @foreach ($announcements as $announcement)
                <div class="mb-4 last:mb-0">
                    <p class="font-semibold">{{ $announcement->title }}</p>
                    <p class="text-sm">{{ $announcement->content }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
