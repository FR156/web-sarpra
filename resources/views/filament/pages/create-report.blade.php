<x-filament-panels::page>
    <x-filament::section>
        <x-filament::card>
            <p class="text-sm text-gray-500">Total Loans:</p>
            <p class="text-2xl font-bold">{{ $totalLoans }}</p>

            <p class="text-sm text-success-600">Approved:</p>
            <p class="text-2xl font-bold">{{ $approved }}</p>

            <p class="text-sm text-danger-600">Rejected:</p>
            <p class="text-2xl font-bold">{{ $rejected }}</p>
        </x-filament::card>
        <x-filament::card>
            <p class="text-sm text-gray-500">Total Items:</p>
            <p class="text-2xl font-bold">{{ $totalItems }}</p>

            <p class="text-sm text-gray-500">Total Units:</p>
            <p class="text-2xl font-bold">{{ $totalUnits }}</p>

            <p class="text-sm text-gray-500">Minor Damage:</p>
            <p class="text-2xl font-bold">{{ $minorDamage }}</p>

            <p class="text-sm text-gray-500">Major Damage:</p>
            <p class="text-2xl font-bold">{{ $majorDamage }}</p>

            <p class="text-sm text-gray-500">Lost Unit:</p>
            <p class="text-2xl font-bold">{{ $lostUnit }}</p>
        </x-filament::card>
    </x-filament::section>

    <form method="POST" action="{{ route('export.report') }}">
        @csrf
        <x-filament::button type="submit">Export PDF</x-filament::button>
    </form>
</x-filament-panels::page>
