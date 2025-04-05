<!DOCTYPE html>

<html lang="en" data-bs-theme-mode="light">
    <!--begin::Head-->

    <head>
        <title>{{ $pageTitle ?? 'Dashboard' }} | {{ config('app.name') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('favicon.png') }}" />

        <!--begin::Fonts(mandatory for all pages)-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <!--end::Fonts-->

        <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        <link href="{{ asset('admin_assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_assets/css/admin.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('jodit/jodit.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <!--end::Global Stylesheets Bundle-->

        <style>
            /* Chrome, Safari, Edge, Opera */
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            /* Firefox */
            input[type=number] {
                -moz-appearance: textfield;
            }

            .tag-container {
                display: flex;
                flex-wrap: wrap;
                border: 1px solid #ccc;
                padding: 5px;
                border-radius: 4px;
                background: #fff;
            }

            .tag {
                background-color: #007bff;
                color: white;
                padding: 5px 10px;
                margin: 2px;
                border-radius: 15px;
                display: flex;
                align-items: center;
            }

            .tag .remove-tag {
                cursor: pointer;
                margin-left: 5px;
                font-weight: bold;
            }

            .tag-input {
                border: none;
                outline: none;
                flex: 1;
                padding: 5px;
            }

            .jodit-container {
                border: 1px solid #ccc !important;
                /* Ensure border is visible */
                overflow: hidden;
                /* Prevent content overflow */
            }

            .jodit-workplace {
                max-height: 180px;
                /* Adjust height to fit */
                overflow-y: auto;
                /* Enable scrolling if needed */
                padding-bottom: 10px;
                /* Ensure space at the bottom */
            }

            .btn-primary {
                color: #fff !important;
            }
        </style>

        @stack('styles')
    </head>
    <!--end::Head-->

    <!--begin::Body-->

    <body id="kt_app_body" class="app-default">
        <!--begin::Page-->
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <!--begin::Header-->
                <x-admin.header :$pageTitle />
                <x-admin.sidebar />
                <!--end::Header-->
                <!--begin::Wrapper-->
                <div class="wrapper d-flex flex-column flex-row-fluid">
                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content"
                        style="background-color: #f4f7fe !important">
                        <!--begin::Post-->
                        <div class="post d-flex flex-column-fluid" id="kt_post">
                            <!--begin::Container-->
                            <div id="kt_content_container-fluid" class="container-fluid">

                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Wrapper-->
            </div>
        </div>
        <!--end::Page-->

        <script src="{{ asset('admin_assets/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('admin_assets/js/scripts.bundle.js') }}"></script>
        <script src="{{ asset('employee_assets/js/custom.js') }}"></script>

        <!--end::Global Javascript Bundle-->

        <!--begin::Vendors Javascript(used for this page only)-->
        <script src="{{ asset('admin_assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <script src="{{ asset('jodit/jodit.min.js') }}"></script>
        <!--end::Vendors Javascript-->

        <x-toastr />

        <script>
            let joditInstances = {};
            // Common jodit options
            const joditOptions = {
                "height": "auto",
                "minHeight": 200,
                "maxHeight": 300,
                "toolbarSticky": false,
                "overflow": "hidden",
                "autoresize": true,
                "disablePlugins": "about,add-new-line,ai-assistant,backspace,clipboard,delete-command,drag-and-drop,drag-and-drop-element,dtd,enter,file,focus,font,format-block,hotkeys,hr,iframe,image,image-processor,image-properties,indent,inline-popup,justify,key-arrow-outside,limit,line-height,link,media,mobile,powered-by-jodit,preview,print,resize-handler,resize-cells,resizer,search,select,select-cells,source,speech-recognize,spellcheck,stat,sticky,symbols,tab,table,table-keyboard-navigation,video,wrap-nodes,xpath",
                "buttons": "bold,italic,underline,ul,ol,paragraph,cut,copy,paste,copyformat,undo,redo",
                "placeholder": 'Start job here...',
            };

            $(document).ready(function() {
                $('.numaric').on('input', function() {
                    var min = $(this).attr('min') ? parseInt($(this).attr('min')) : null;
                    var max = $(this).attr('max') ? parseInt($(this).attr('max')) : null;
                    var cleanedValue = $(this).val().replace(/[^0-9]/g, '');

                    if (cleanedValue !== '') {
                        var numValue = parseInt(cleanedValue);

                        if (min !== null && numValue < min) {
                            numValue = min;
                        }

                        if (max !== null && numValue > max) {
                            numValue = max;
                        }

                        $(this).val(numValue);
                    } else {
                        $(this).val('');
                    }
                });

                $("textarea.editor").each(function() {
                    let formId = $(this).closest("form").attr("id"); // Get form ID
                    let name = $(this).attr("name"); // Get textarea name
                    let uniqueSelector = `#${formId} [name='${name}']`; // Unique selector per form

                    createJodit(uniqueSelector); // Initialize Jodit
                });
            });

            // Helper Function: Validate URL
            function isValidURL(url) {
                var pattern = new RegExp("^(https?:\\/\\/)?(www\\.)?([a-zA-Z0-9._-]+)\\.[a-zA-Z]{2,}([\\/a-zA-Z0-9#?=&_.-]*)?$",
                    "i");
                return pattern.test(url);
            }

            function initializeTagInput(containerSelector) {
                $(containerSelector).each(function() {
                    var $tagContainer = $(this);
                    var $input = $tagContainer.find(".tag-input");

                    // Handle tag addition on Enter or Comma keypress
                    $input.on("keypress", function(e) {
                        if (e.which === 44 || e.which === 13) { // Comma (,) or Enter key
                            e.preventDefault();
                            var tagText = $input.val().trim();
                            if (tagText !== "") {
                                addTag($tagContainer, tagText);
                                $input.val(""); // Clear input field
                            }
                        }
                    });

                    // Load existing categories if available
                    var existingCategories = $input.data("tags"); // Fetch categories from data-tags
                    if (existingCategories) {
                        existingCategories.split(",").forEach(function(tag) {
                            addTag($tagContainer, tag.trim());
                        });
                    }

                    // Function to add a tag
                    function addTag(container, text) {
                        var existingTags = container.find(".tag").map(function() {
                            return $(this).clone().children().remove().end().text();
                        }).get();

                        if (existingTags.includes(text)) return; // Prevent duplicate tags

                        var $tag = $("<span/>", {
                            "class": "tag",
                            text: text
                        });
                        var $removeBtn = $("<span/>", {
                            "class": "remove-tag",
                            text: " ×"
                        });

                        // Append remove button to tag
                        $tag.append($removeBtn);
                        $tag.insertBefore($input);

                        // Remove tag when clicked
                        $removeBtn.on("click", function() {
                            $(this).parent().remove();
                        });
                    }

                    // Preload tags if editing existing categories
                    var existingCategories = $input.data("tags"); // Assume this comes from backend
                    if (existingCategories) {
                        existingCategories.split(",").forEach(function(tag) {
                            addTag($tagContainer, tag.trim());
                        });
                    }
                });
            }

            function createJodit(selector, options = {}) {
                let element = $(selector)[0]; // Get the textarea element

                if (!element) {
                    console.error("Invalid element, Jodit cannot be initialized.");
                    return null;
                }

                let editor = new Jodit(element, {
                    ...joditOptions,
                    ...options
                });

                joditInstances[selector] = editor; // Store instance uniquely

                return editor;
            }

            function isJoditContentEmpty(content) {
                // Remove HTML tags and whitespace
                let cleanedContent = content.replace(/<[^>]*>/g, "").trim();
                return cleanedContent === ""; // Returns true if content is empty
            }

            function showError(inputField, message) {
                inputField.addClass("is-invalid");
                inputField.after('<div class="invalid-feedback d-block">' + message + '</div>');
            }
        </script>

        @stack('scripts')

    </body>

</html>
