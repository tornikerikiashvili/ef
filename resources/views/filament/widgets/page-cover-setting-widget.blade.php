<x-filament::widget>
    <x-filament::section :heading="$this->heading()">
        <form wire:submit.prevent="save">
            @if($currentPath)
                <div class="mb-4">
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($currentPath) }}"
                        alt="{{ $this->heading() }}"
                        style="max-height: 220px; width: 100%; object-fit: cover; border-radius: 10px;"
                    >
                </div>
            @endif

            <div class="space-y-2">
                <input
                    id="cover-upload-{{ $this->getId() }}"
                    type="file"
                    wire:model="upload"
                    accept="image/*"
                    style="display: none;"
                >

                <div class="mt-6" style="margin-top:10px; margin-bottom:10px;">
                    <label for="cover-upload-{{ $this->getId() }}"
                        class="fi-btn fi-btn-primary cursor-pointer mt-4 mb-2">
                        Upload Image
                    </label>
                </div>

                <div wire:loading wire:target="upload" class="text-sm text-gray-500 dark:text-gray-400">
                    Uploading…
                </div>

                @if($upload)
                    <div class="text-sm text-gray-700 dark:text-gray-300 mt-2">
                        <span class="font-medium">Selected:</span>
                        {{ $upload->getClientOriginalName() }}
                    </div>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                        Not saved yet — click Save below to use this image on the {{ $this->listingLabel() }} listing page.
                    </p>
                @endif

                @error('upload')
                    <div class="text-sm text-danger-600">{{ $message }}</div>
                @enderror

            </div>

            <div class="mt-4">
                <x-filament::button type="submit">
                    Save
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament::widget>
