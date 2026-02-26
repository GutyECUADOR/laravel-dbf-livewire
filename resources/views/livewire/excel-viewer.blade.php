<div>
    <form>

        @error('archivo') <span class="error">{{ $message }}</span> @enderror
        @if(Session::has('message'))
        <p class="alert alert-info">{{ Session::get('message') }}</p>
        @endif


        <label class="block mb-2.5 text-sm font-medium text-heading" for="file_input"> {{ __('Select DBF file') }}</label>
        <input
            class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full shadow-xs placeholder:text-body"
            aria-describedby="file_input_help"
            placeholder="Seleccione el archivo"
            id="file_input"
            type="file" wire:model.live="archivo">

        <div wire:loading wire:target="archivo">Subiendo archivo...</div>

        <x-primary-button type="submit" class="mt-3 nline-flex items-center px-4 py-2 bg-blue-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            {{ __('Save') }}
        </x-primary-button>

        <!-- Display the contents of the DBF file if it has been uploaded -->
        <div
            class="relative flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md rounded-xl bg-clip-border">
            <table class="min-w-full w-full text-left table-auto min-w-max">
                <thead>
                    <tr>
                        <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                            <p class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                                Código
                            </p>
                        </th>
                        <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                            <p class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                                Artículo
                            </p>
                        </th>
                        <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                            <p class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                                Precio
                            </p>
                        </th>
                        <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                            <p class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70"></p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $fila)
                    <tr>
                        @foreach($fila as $columna)
                        <td class="p-4 border-b border-blue-gray-50">
                            <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                                {{ $columna }}
                            </p>
                        </td>
                        @endforeach
                        <td class="p-4 border-b border-blue-gray-50">
                            <a href="#" class="block font-sans text-sm antialiased font-medium leading-normal text-blue-gray-900">
                                Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </form>
</div>