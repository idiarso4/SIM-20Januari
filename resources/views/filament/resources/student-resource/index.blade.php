<x-filament::page>
    @push('scripts')
        <script>
            window.addEventListener('refreshComponent', event => {
                // Refresh table
                @this.call('refresh')
            })
        </script>
    @endpush
</x-filament::page> 