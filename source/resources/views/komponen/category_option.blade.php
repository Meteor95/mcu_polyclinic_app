<option value="{{ $category->id }}">{{ str_repeat('--', $depth) }} {{ $category->nama_kategori }}</option>
@foreach ($category->children as $child)
    @include('komponen.category_option', ['category' => $child, 'depth' => $depth + 1])
@endforeach
