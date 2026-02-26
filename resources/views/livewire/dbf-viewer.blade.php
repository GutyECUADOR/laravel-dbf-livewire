<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-gray-900">Visor dBase (XBase)</h1>
            <p class="text-gray-500">Carga archivos .dbf para visualizar su contenido instantáneamente.</p>
        </div>

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 mb-8">
            <div class="flex items-center justify-center w-full">
                <label class="flex flex-col w-full h-32 border-4 border-dashed border-blue-200 hover:bg-gray-50 hover:border-blue-300 transition-colors cursor-pointer">
                    <div class="flex flex-col items-center justify-center pt-7">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-400 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="pt-1 text-sm tracking-wider text-gray-400">Seleccionar archivo DBF</p>
                    </div>
                    <input type="file" wire:model="dbfFile" class="opacity-0" accept=".dbf" />
                </label>
            </div>
            
            <div wire:loading wire:target="dbfFile" class="mt-4 flex items-center text-blue-600 font-semibold">
                <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24"></svg>
                Procesando datos con XBase...
            </div>
        </div>

        <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">
            @if(count($rows) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach($headers as $header)
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b">
                                        {{ $header }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($rows as $row)
                                <tr class="hover:bg-blue-50/50 transition-colors">
                                    @foreach($headers as $header)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                            {{ $row[$header] }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t text-xs text-gray-400">
                    * Mostrando una vista previa de los primeros registros cargados.
                </div>
            @else
                <div class="p-20 text-center">
                    <span class="text-gray-300">Esperando archivo...</span>
                </div>
            @endif
        </div>
    </div>
</div>