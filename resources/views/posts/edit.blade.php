<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="relative overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <x-jet-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="mb-4 text-sm font-medium text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('posts.update', $post) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-jet-label for="title" value="{{ __('Title') }}" />
                            <x-jet-input id="title" class="block w-full mt-1" type="text" name="title" :value="old('title', $post->title)" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-jet-label for="content" value="{{ __('Content') }}" />
                            <x-rich-text id="content" name="content" value="{!! old('content', $post->content->toTrixHtml()) !!}" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('posts.show', $post) }}">
                                {{ __('Cancel') }}
                            </a>

                            <x-jet-button class="ml-4">
                                {{ __('Save') }}
                            </x-jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
