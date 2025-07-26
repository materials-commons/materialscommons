{{--
    Example of how to use the EditableMarkdown component

    This example demonstrates:
    1. Basic usage with default settings
    2. Using with initial content
    3. Listening for the save event
    4. Using a custom save event name
--}}

<div class="container py-4">
    <h1 class="display-5 fw-bold mb-4">EditableMarkdown Component Examples</h1>

    <div class="mb-5">
        <!-- Example 1: Basic Usage -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title h4 mb-2">Basic Usage</h2>
                <p class="mb-4 text-secondary">The simplest way to use the component with no initial content.</p>

                <div class="bg-light p-4 rounded mb-3">
                    <livewire:editable-markdown />
                </div>

                <div class="mt-3">
                    <pre class="bg-light p-2 rounded"><code>&lt;livewire:editable-markdown /&gt;</code></pre>
                </div>
            </div>
        </div>

        <!-- Example 2: With Initial Content -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title h4 mb-2">With Initial Content</h2>
                <p class="mb-4 text-secondary">Providing initial Markdown content to display.</p>

                <div class="bg-light p-4 rounded mb-3">
                    <livewire:editable-markdown
                        content="# Hello World

This is an example of the **EditableMarkdown** component with initial content.

- Item 1
- Item 2
- Item 3

[Link to Materials Commons](https://materialscommons.org)"
                    />
                </div>

                <div class="mt-3">
                    <pre class="bg-light p-2 rounded"><code>&lt;livewire:editable-markdown
    content="# Hello World

This is an example of the **EditableMarkdown** component with initial content.

- Item 1
- Item 2
- Item 3

[Link to Materials Commons](https://materialscommons.org)"
/&gt;</code></pre>
                </div>
            </div>
        </div>

        <!-- Example 3: Listening for Save Event -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title h4 mb-2">Listening for Save Event</h2>
                <p class="mb-4 text-secondary">You can listen for the 'markdownSaved' event to handle saved content.</p>

                <div class="bg-light p-4 rounded mb-3">
                    <livewire:editable-markdown
                        content="## Editable Content

Edit this content and save to see the event being dispatched."
                        id="example-with-event"
                    />
                </div>

                <div class="mt-3">
                    <pre class="bg-light p-2 rounded"><code>&lt;livewire:editable-markdown
    content="## Editable Content

Edit this content and save to see the event being dispatched."
    id="example-with-event"
/&gt;

&lt;script&gt;
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('markdownSaved', (data) => {
            console.log('Markdown saved:', data.content);
            // Handle the saved content here
        });
    });
&lt;/script&gt;</code></pre>
                </div>
            </div>
        </div>

        <!-- Example 4: Custom Save Event -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title h4 mb-2">Custom Save Event</h2>
                <p class="mb-4 text-secondary">You can specify a custom event name to be dispatched when content is saved.</p>

                <div class="bg-light p-4 rounded mb-3">
                    <livewire:editable-markdown
                        content="## Custom Event Example

This component uses a custom event name."
                        save-event="descriptionSaved"
                        id="example-custom-event"
                    />
                </div>

                <div class="mt-3">
                    <pre class="bg-light p-2 rounded"><code>&lt;livewire:editable-markdown
    content="## Custom Event Example

This component uses a custom event name."
    save-event="descriptionSaved"
    id="example-custom-event"
/&gt;

&lt;script&gt;
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('descriptionSaved', (data) => {
            console.log('Description saved:', data.content);
            // Handle the saved content here
        });
    });
&lt;/script&gt;</code></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for the examples -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Example 3: Listen for the default save event
            Livewire.on('markdownSaved', (data) => {
                console.log('Markdown saved:', data.content);
                // In a real application, you might want to show a notification or perform other actions
            });

            // Example 4: Listen for the custom save event
            Livewire.on('descriptionSaved', (data) => {
                console.log('Description saved:', data.content);
                // Handle the saved content here
            });
        });
    </script>
</div>
