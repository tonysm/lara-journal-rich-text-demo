<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="relative overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="p-8">
                    {!! $post->content !!}
                </div>

                <div class="absolute top-2 right-2">
                    <a href="{{ route('posts.edit', $post) }}" title="Edit"><x-icon class="w-4 h-4 text-gray-400 transition transform-all hover:text-gray-600 hover:scale-125" type="pencil" /></a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
