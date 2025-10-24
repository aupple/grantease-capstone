<x-app-layout>
    <div class="p-6">
        <h1 class="text-xl font-bold mb-4">Notifications</h1>

        @if($notifications->count() > 0)
            <ul class="space-y-3">
                @foreach($notifications as $notification)
                    <li class="p-3 border rounded bg-white shadow">
                        <a href="{{ $notification->data['url'] ?? '#' }}" class="text-blue-600 hover:underline">
                            {{ $notification->data['message'] ?? 'New Notification' }}
                        </a>
                        <div class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">No new notifications</p>
        @endif
    </div>
</x-app-layout>
