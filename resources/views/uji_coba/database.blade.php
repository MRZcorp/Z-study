@foreach ($tugas as $item)
  <div class="bg-white border rounded-xl p-6 space-y-4">
    <div class="flex justify-between">
      <h3 class="font-semibold">{{ $item->judul }}</h3>
      <span class="px-3 py-1 text-xs rounded-full">
        {{ $item->status }}
      </span>
    </div>
  </div>
@endforeach
