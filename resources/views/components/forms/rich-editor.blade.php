<div class="form-group mb-3" wire:ignore>
    @if ($label)
        <label for="{{ $id }}" class="block text-md font-semibold text-gray-700 dark:text-gray-200 mb-2">
            {{ $label }}
        </label>
    @endif
    <div x-data="{
        editor: null,
        content: @this.get('{{ $name }}'),
        init() {
            this.editor = new Quill($refs.editor, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        ['link', 'blockquote', 'code-block'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['clean']
                    ]
                }
            });
    
            if (this.content) {
                this.editor.root.innerHTML = this.content;
                @this.set('{{ $name }}', this.content);
            }
            this.editor.on('text-change', () => {
                this.content = this.editor.root.innerHTML;
                @this.set('{{ $name }}', this.content);
            });
        }
    }">
        <div x-ref="editor" id="{{ $id }}" style="min-height: {{ $height }};" class="dark:text-gray-300"></div>
    </div>
    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
