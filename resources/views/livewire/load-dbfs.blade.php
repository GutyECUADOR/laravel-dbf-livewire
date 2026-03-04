<div class="p-6">
    <div class="max-w-7xl mx-auto space-y-6">

        @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded shadow-sm">
            <div class="flex">
                <div class="text-red-700 text-sm">{{ session('error') }}</div>
            </div>
        </div>
        @endif

        @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded shadow-sm">
            <div class="flex">
                <div class="text-green-700 text-sm">{{ session('success') }}</div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Cargar Archivo DBF</h2>

            <div class="flex items-center gap-4">
                <label class="relative cursor-pointer bg-red-600 text-white p-4 hover:bg-blue-800 font-semibold py-2 px-4 rounded-lg transition duration-200 shadow-md">
                    <span>Seleccionar archivo</span>
                    <input type="file" wire:model="dbfFile" class="hidden" accept=".dbf">
                </label>

                <div wire:loading wire:target="dbfFile" class="flex items-center text-gray-600">
                    <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Leyendo tablas con XBase...</span>
                </div>

                @if($recordCount > 0)
                <button wire:click="import" wire:loading.attr="disabled" class="bg-red-600 hover:bg-blue-800 text-white px-6 py-2 rounded-lg font-bold transition flex items-center">
                    <span wire:loading.remove wire:target="import">Confirmar e Importar {{ $recordCount }} registros</span>
                    <span wire:loading wire:target="import">Procesando base de datos...</span>
                </button>

                <button wire:click="updateDbfFromDatabase"
                    wire:loading.attr="disabled"
                    class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded shadow transition">
                    <span wire:loading.remove wire:target="updateDbfFromDatabase">
                        Actualizar DBF con precios desde la base de datos
                            </span>
                            <span wire:loading wire:target="updateDbfFromDatabase">
                                Escribiendo en DBF...
                            </span>
                </button>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Contenido del archivo</span>
                @if($recordCount > 0)
                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">
                    {{ $recordCount }} registros totales
                </span>
                @endif
            </div>

            <div class="overflow-x-auto">
                @if(count($rows) > 0)
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            @foreach($headers as $header)
                            <th class="px-6 py-3 font-semibold uppercase tracking-wider border-r border-gray-700 last:border-0">
                                {{ $header }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($rows as $index => $row)
                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-blue-50 transition-colors">
                            @foreach($headers as $header)
                            <td class="px-6 py-4 text-gray-700 whitespace-nowrap border-r border-gray-100 last:border-0">
                                {{ $row[$header] ?? '-' }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="py-20 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay datos</h3>
                    <p class="mt-1 text-sm text-gray-500">Sube un archivo para previsualizar los registros.</p>
                </div>
                @endif
            </div>

            @if(count($rows) > 0)
            <div class="p-4 bg-gray-50 border-t border-gray-200 text-xs text-gray-400 italic">
                * Vista previa limitada a los primeros 50 registros.
            </div>
            @endif
        </div>
    </div>
</div>