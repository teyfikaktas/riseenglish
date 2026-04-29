{{-- resources/views/admin/word-set-categories/_row.blade.php --}}
<tr class="hover:bg-gray-50 transition-colors duration-200">
    <td class="px-6 py-3 whitespace-nowrap">
        <div class="flex items-center" style="padding-left: {{ $depth * 24 }}px">
            {{-- Ok ikonu (alt kategorisi varsa) --}}
            @if($depth > 0)
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            @endif

            {{-- Renk nokta --}}
            <span class="inline-block w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background-color: {{ $category->color }}"></span>

            <span class="text-sm font-medium text-gray-900">{{ $category->name }}</span>

            {{-- Alt kategori sayısı badge --}}
            @if($category->children->count() > 0)
                <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">
                    {{ $category->children->count() }} alt
                </span>
            @endif
        </div>
    </td>
    <td class="px-6 py-3 whitespace-nowrap">
        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-mono" style="background-color: {{ $category->color }}20; color: {{ $category->color }}">
            {{ $category->color }}
        </span>
    </td>
    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
        {{ $category->sort_order }}
    </td>
    <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
        <div class="flex space-x-2 justify-end">
            {{-- Alt kategori ekle --}}
            <a href="{{ route('admin.word-set-categories.create', ['parent_id' => $category->id]) }}"
               class="text-green-600 hover:text-green-900 transition-colors" title="Alt Kategori Ekle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
            </a>
            {{-- Düzenle --}}
            <a href="{{ route('admin.word-set-categories.edit', $category) }}"
               class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Düzenle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
            </a>
            {{-- Sil --}}
            <button onclick="confirmDelete('{{ $category->id }}')"
                    class="text-red-600 hover:text-red-900 transition-colors" title="Sil">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
            <form id="delete-form-{{ $category->id }}" action="{{ route('admin.word-set-categories.destroy', $category) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </td>
</tr>

{{-- Alt kategorileri recursive render et --}}
@foreach($category->children as $child)
    @include('admin.word-set-categories._row', ['category' => $child, 'depth' => $depth + 1])
@endforeach
