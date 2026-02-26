<div>
    <form wire:submit.prevent="save">

        @error('dbfFile') <span class="error">{{ $message }}</span> @enderror
        @if(Session::has('message'))
        <p class="alert alert-info">{{ Session::get('message') }}</p>
        @endif
 

        <label class="block mb-2.5 text-sm font-medium text-heading" for="file_input"> {{ __('Select DBF file') }}</label>
        <input 
            class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full shadow-xs placeholder:text-body" 
            aria-describedby="file_input_help" 
            id="file_input" 
            type="file" wire:model="dbfFile">
        
        <x-primary-button type="submit" class="mt-3 nline-flex items-center px-4 py-2 bg-blue-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            {{ __('Save') }}
        </x-primary-button>
    
    </form>
</div>