# EditableMarkdown Component Save Event Documentation

## Overview

The `EditableMarkdown` component has been updated to remove direct download functionality. Instead, it now emits a Livewire event when the content is saved, allowing other components to listen for this event and handle the save and download operations.

## Changes Made

1. Removed download-related functionality from the `EditableMarkdown` component:
   - Removed properties: `$showDownloadModal` and `$downloadFilename`
   - Removed methods: `toggleDownloadModal()` and `download()`
   - Removed UI elements: Download buttons and the download modal

2. Added a Save button in display mode that calls the `save()` method, which emits the `markdownSaved` event with the content.

## How to Implement a Listener for the Save Event

### 1. In a Parent Livewire Component

If you're using the `EditableMarkdown` component within another Livewire component, you can listen for the save event like this:

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class ParentComponent extends Component
{
    public function handleMarkdownSaved($content)
    {
        // Handle the saved markdown content
        // For example, save it to a file or database
        
        // To download the content as a file:
        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, 'markdown.md');
    }
    
    public function render()
    {
        return view('livewire.parent-component');
    }
}
```

In your Blade template:

```blade
<div>
    <livewire:editable-markdown save-event="markdownSaved" />
    
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('markdownSaved', (event) => {
                // You can also handle the event in JavaScript
                console.log('Markdown saved:', event.content);
            });
        });
    </script>
</div>
```

### 2. In a Blade View with Alpine.js

If you're using the `EditableMarkdown` component directly in a Blade view, you can listen for the event using Alpine.js:

```blade
<div x-data="{ 
    handleMarkdownSaved(content) {
        // Handle the saved markdown content
        console.log('Markdown saved:', content);
        
        // For example, you could trigger a download using JavaScript:
        const blob = new Blob([content], { type: 'text/markdown' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'markdown.md';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
}">
    <livewire:editable-markdown save-event="markdownSaved" />
    
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('markdownSaved', (content) => {
                Alpine.evaluate(document.querySelector('[x-data]'), 'handleMarkdownSaved', content);
            });
        });
    </script>
</div>
```

### 3. In a Controller

If you need to handle the save event in a controller, you can create a dedicated Livewire component that wraps the `EditableMarkdown` component and handles the save event:

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class MarkdownEditor extends Component
{
    public function downloadMarkdown($content)
    {
        // Handle the saved markdown content
        // For example, save it to a file or database
        
        // To download the content as a file:
        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, 'markdown.md');
    }
    
    public function render()
    {
        return view('livewire.markdown-editor');
    }
}
```

In your Blade template:

```blade
<div>
    <livewire:editable-markdown save-event="markdownSaved" />
    
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('markdownSaved', (event) => {
                @this.downloadMarkdown(event.content);
            });
        });
    </script>
</div>
```

## Custom Event Name

You can customize the event name by passing the `save-event` parameter to the `EditableMarkdown` component:

```blade
<livewire:editable-markdown save-event="customEventName" />
```

Then listen for this custom event name instead of the default `markdownSaved`.
