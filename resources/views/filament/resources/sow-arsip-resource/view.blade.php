<x-filament::page>
    {{-- INFO HEADER ARSIP --}}
    <x-filament::section>
        <div class="space-y-1">
            <h2 class="text-xl font-bold">
                {{ $record->judul }}
            </h2>

            <p class="text-sm text-gray-500">
                Jumlah Item:
                <span class="font-semibold">
                    {{ $record->items()->count() }}
                </span>
            </p>

            <p class="text-sm text-gray-500">
                Tanggal Arsip:
                {{ $record->created_at?->translatedFormat('d F Y H:i') }}
            </p>
        </div>
    </x-filament::section>

    {{-- KONDISI: ADA DATA ATAU TIDAK --}}
    @if ($record->items()->exists())
        <x-filament::section>
            {{ $this->table }}
        </x-filament::section>
    @else
        <x-filament::section>
            <div class="text-center py-10 text-gray-500">
                <p class="text-lg font-semibold">Tidak ada data arsip</p>
                <p class="text-sm">
                    Arsip ini belum memiliki item yang tersimpan.
                </p>
            </div>
        </x-filament::section>
    @endif
</x-filament::page>
