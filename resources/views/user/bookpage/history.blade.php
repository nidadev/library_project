<x-user.layout pageTitle="Book Page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Borrow History</h3>

                    </div>
                    <div class="card-body">
                        @if ($borrowhistory->isEmpty())
                        <x-empty-state title="No Borrow history Yet"
                            message="You haven't borrow any book."
                            buttonText="Borrow" modalTarget="#addBookPageModal" />
                        @else
                        <table class="table table-bordered" id="bookpage_table">
                            <thead>
                                <tr>
                                    <th>Books Name Borrowed</th>
                                    <th>Status</th>
                                    <th>Applied Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($borrowhistory as $bh)
                                <tr>
                                    <td>{{ $bh->book->name }}</td>
                                    <td>{{ ucfirst($bh->status) }}</td>
                                    <td>{{ $bh->book->created_at->format('d M Y') }}</td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Add BookPage Modal -->

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @push('scripts')

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#bookpage_table').DataTable({
                "order": []
            });

            initializeTagInput(".tag-container");

            // Clear Validation when Create Book Page Modal close
            $('#addCertificateForm').on('hidden.bs.modal', function(event) {
                $('#addCertificateForm').find('.fv-row').find('.fv-plugins-message-container').empty();
                $('#addCertificateForm input').removeClass('is-invalid');
                $('#addCertificateForm textarea').removeClass('is-invalid');
                $('#addCertificateForm')[0].reset();
            });

            // Clear Validation when Edit Book Page Modal closes
            $(document).on('hidden.bs.modal', '[id^=editBookPageModal_]', function(event) {
                var BookPageId = $(this).attr('id').split('_')[1]; // Fix index issue

                var form = $('#editBookPageForm_' + BookPageId);

                form.find('.fv-plugins-message-container').empty();
                form.find('input, textarea').removeClass('is-invalid');
                form[0].reset();

                // Hide file preview & show file input
                $('#editBookPageFilePreview_' + BookPageId).hide();
                $('#edit_BookPage_file_' + BookPageId).show(); // Fix incorrect selector
            });

            //We have to show the .bookpage-file when we open the modal
            $(document).on('show.bs.modal', '[id^=editBookPageModal_]', function(event) {
                var BookPageId = $(this).attr('id').split('_')[1]; // Fix index issue
                $('#editBookPageFilePreview_' + BookPageId).hide();
                $('.BookPage-file').show();
                $('.BookPage-pdf').show();
            });

            // Create Book Page Modal Form - Submission event listener

            $('#addCertificateForm').on('submit', function(e) {
                alert('');
                e.preventDefault(); // Prevent default form submission
                if (validateCreateBookPageForm()) {
                    $('#createbookPageBtn').attr("data-kt-indicator", "on");

                    // Get the form data

                    var formId = $(this).attr('id');
                    var formData = new FormData($('#' + formId)[0]);

                    // Add CSRF token to the form data
                    formData.append('_token', '{{ csrf_token() }}');
                    var categories = $('#bookpage_categories_create').closest(
                            '.tag-container')
                        .find('.tag')
                        .map(function() {
                            return $(this).text().replace(' ×', '');
                        })
                        .get()
                        .join(',');

                    formData.append('categories', categories);
                    $.ajax({
                        url: "{{ route('admin.bookpage.store') }}",
                        type: 'POST',

                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            //alert(data.message);
                            console.log(response.message);
                            $('#createbookPageBtn').removeAttr("data-kt-indicator", "on");

                            // Reset validation messages and styles
                            $('.fv-plugins-message-container').text('').hide();
                            $('#' + formId + ' input').removeClass('is-invalid');
                            $('#' + formId + ' textarea').removeClass('is-invalid');

                            // Redirect the page or reload it
                            window.location.href = response.redirect_url;

                            // Reset the form and close the modal
                            $('#' + formId)[0].reset();
                            $("#addBookPageModal").modal("hide");
                        },
                        error: function(data) {
                            alert(data.message);
                            console.log(data.message);


                            var errors = data.responseJSON;
                            console.log(errors);
                        }



                    });
                }
            });


            // Update BookPage Modal Form - Submission event listener
            $(document).on('submit', '[id^=editBookPageForm_]', function(e) {
                //alert('')
                e.preventDefault(); // Prevent default form submission

                var formId = $(this).attr('id');
                var BookPageId = formId.split('_')[1]; // Extract BookPage ID
                var updateButton = $('#updateBookPageBtn_' + BookPageId);
                //alert(BookPageId);

                // Validate the form before submitting
                if (validateUpdateBookPageForm(BookPageId)) {
                    //alert('2');
                    updateButton.attr("data-kt-indicator", "on"); // Show loading indicator

                    // Get the form data
                    var formData = new FormData($('#' + formId)[0]);

                    // Add CSRF token to the form data
                    formData.append('_token', '{{ csrf_token() }}');

                    // Get all category tags and append them as a single input
                    var categories = $('#edit_book_categories_' + BookPageId).closest(
                            '.tag-container')
                        .find('.tag')
                        .map(function() {
                            return $(this).text().replace(' ×', '');
                        })
                        .get()
                        .join(',');

                    formData.append('categories', categories);
                    alert(formData);

                    // Send the form data using AJAX
                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.success) {
                                updateButton.removeAttr("data-kt-indicator", "on");

                                // Reset validation messages and styles
                                $('.fv-plugins-message-container').text('').hide();
                                $('#' + formId + ' input').removeClass('is-invalid');
                                $('#' + formId + ' textarea').removeClass('is-invalid');

                                // Redirect the page or reload it
                                window.location.href = response.redirect_url;

                                // Reset the form and close the modal
                                $('#' + formId)[0].reset();
                                $("#editBookPageModal_" + BookPageId).modal("hide");
                            }
                        },
                        error: function(xhr) {
                            updateButton.removeAttr("data-kt-indicator", "on");

                            $('.fv-plugins-message-container').text('').hide();
                            $('#' + formId + ' input').removeClass('is-invalid');
                            $('#' + formId + ' textarea').removeClass('is-invalid');

                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                for (var key in errors) {
                                    var fieldId = '#edit_' + key + '_' + BookPageId;
                                    $(fieldId).addClass('is-invalid');
                                    $(fieldId).closest('.fv-row')
                                        .find('.fv-plugins-message-container').text(errors[key][
                                            0
                                        ]).show();
                                }
                            } else if (xhr.status === 403) {
                                Swal.fire({
                                    title: 'Error',
                                    text: xhr.responseJSON.message,
                                    icon: 'error',
                                    showConfirmButton: true,
                                });
                            } else if (xhr.status === 500) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Failed to update the BookPage. Please try again later.',
                                    icon: 'error',
                                    showConfirmButton: true,
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'An unexpected error occurred. Please try again.',
                                    icon: 'error',
                                    showConfirmButton: true,
                                });
                            }
                        }
                    });
                }
            });

            // Image Preview Functionality
            $('#book_image').change(function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#BookPageFilePreview').attr('src', e.target.result).show();
                    }

                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                } else {
                    $('#BookPageFilePreview').hide();
                }
            });

            // Image Preview Functionality for Edit BookPage Modal
            $(document).on('change', '[id^=edit_book_image_]', function() {
                var input = this;
                var BookPageId = $(this).attr('id').split('_')[3];

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#editBookPageFilePreview_' + BookPageId).attr('src', e.target.result)
                            .show();

                        // we will hide BookPage-file when we showing the preview
                        $('.BookPage-file').hide();
                    }

                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                } else {
                    $('#editBookPageFilePreview_' + BookPageId).hide();
                    $('.BookPage-file').show();
                }
            });

            // Pdf Preview Functionality for Edit BookPage Modal
            $(document).on('change', '[id^=edit_book_pdf_]', function() {
                var input = this;
                var BookPageId = $(this).attr('id').split('_')[3];

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#editBookPageFilePdfPreview_' + BookPageId).attr('src', e.target.result)
                            .show();

                        // we will hide BookPage-file when we showing the preview
                        $('.BookPage-pdf').hide();
                    }

                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                } else {
                    $('#editBookPageFilePdfPreview_' + BookPageId).hide();
                    $('.BookPage-pdf').show();
                }
            });

        });

        // Validate Create BookPage Form
        function validateCreateBookPageForm() {
            var isValid = true;
            var maxSize = 20 * 1024 * 1024; // 20MB in Bytes
            var allowedTypes = ['image/jpeg', 'image/jpg', 'image/webp'];
            var allowedTypesPdf = ['pdf'];

            // Validate BookPage Name
            var bookpageName = $('#book_name').val().trim();
            if (bookpageName === '') {
                $('#book_name').addClass('is-invalid');
                $('#book_name').closest('.fv-row').find('.fv-plugins-message-container').text(
                    'Book Title is required.').show();
                isValid = false;
            } else {
                $('#book_name').removeClass('is-invalid');
                $('#book_name').closest('.fv-row').find('.fv-plugins-message-container').empty();
            }

            var pdfName = $('#book_pdf').val().trim();
            if (pdfName === '') {
                $('#book_pdf').addClass('is-invalid');
                $('#book_pdf').closest('.fv-row').find('.fv-plugins-message-container').text(
                    'Book Pdf is required.').show();
                isValid = false;
            } else {
                $('#book_pdf').removeClass('is-invalid');
                $('#book_pdf').closest('.fv-row').find('.fv-plugins-message-container').empty();
            }

            var coverImage = $('#book_image').val().trim();
            if (coverImage === '') {
                $('#book_image').addClass('is-invalid');
                $('#book_image').closest('.fv-row').find('.fv-plugins-message-container').text(
                    'Book cover is required.').show();
                isValid = false;
            } else {
                $('#book_image').removeClass('is-invalid');
                $('#book_image').closest('.fv-row').find('.fv-plugins-message-container').empty();
            }

            // Validate Release year Name
            var releaseYear = $('#release_year').val().trim();
            if (releaseYear === '') {
                $('#release_year').addClass('is-invalid');
                $('#release_year').closest('.fv-row').find('.fv-plugins-message-container').text(
                    'Book Year is required.').show();
                isValid = false;
            } else {
                $('#release_year').removeClass('is-invalid');
                $('#release_year').closest('.fv-row').find('.fv-plugins-message-container').empty();
            }

            // Validate BookPage Description its not null and max to 500 characters
            // var bookpageDescription = $('#book_description').val();
            // if (bookpageDescription != '') {
            //     if (bookpageDescription.trim().length > 500) {
            //         $('#book_description').addClass('is-invalid');
            //         $('#book_description').closest('.fv-row').find('.fv-plugins-message-container').text(
            //             'BookPage Description must not exceed 500 characters.').show();
            //         isValid = false;
            //     } else {
            //         $('#book_description').removeClass('is-invalid');
            //         $('#book_description').closest('.fv-row').find('.fv-plugins-message-container').empty();
            //     }
            // }



            // Validate BookPage Categories (Custom Tag Input)
            var $tagContainer = $('#bookpage_categories_create').closest('.tag-container');
            var tagsCount = $tagContainer.find('.tag').length;

            if (tagsCount === 0) {
                $tagContainer.addClass('is-invalid');
                $tagContainer.closest('.fv-row').find('.fv-plugins-message-container')
                    .text('At least one genre is required.').show();
                isValid = false;
            } else {
                $tagContainer.removeClass('is-invalid');
                $tagContainer.closest('.fv-row').find('.fv-plugins-message-container').empty();
            }

            // Validate BookPage Image File
            var BookPageImageFile = $('#book_image');
            var file = BookPageImageFile[0].files[0];

            if (file) {
                if (file.size > maxSize) {
                    BookPageImageFile.addClass('is-invalid');
                    BookPageImageFile.closest('.fv-row').find('.fv-plugins-message-container').text(
                        'BookPage File should not exceed 20 MB.').show();
                    isValid = false;
                    $('#BookPageFilePreview').hide();
                } else if (allowedTypes.indexOf(file.type) === -1) {
                    BookPageImageFile.addClass('is-invalid');
                    BookPageImageFile.closest('.fv-row').find('.fv-plugins-message-container').text(
                        'Only JPEG, JPG, and Webp file types are allowed.').show();
                    isValid = false;
                    $('#BookPageFilePreview').hide();
                } else {
                    BookPageImageFile.removeClass('is-invalid');
                    BookPageImageFile.closest('.fv-row').find('.fv-plugins-message-container').empty();
                }
            }

            //validate pdf file
            var BookPagePdfFile = $('#book_pdf');
            var file_pdf = BookPagePdfFile[0].files[0];
            // alert(file_pdf);
            var fileExtension = file_pdf.name.split('.').pop().toLowerCase();


            if (file_pdf) {
                if (file_pdf.size > maxSize) {
                    BookPagePdfFile.addClass('is-invalid');
                    BookPagePdfFile.closest('.fv-row').find('.fv-plugins-message-container').text(
                        'BookPage Pdf should not exceed 20 MB.').show();
                    isValid = false;
                    //$('#BookPageFilePreview').hide();
                    //alert(fileExtension);
                }
                if (!allowedTypesPdf.includes(fileExtension)) {
                    BookPagePdfFile.addClass('is-invalid');
                    BookPagePdfFile.closest('.fv-row').find('.fv-plugins-message-container').text(
                        'Only Pdf file types are allowed.').show();
                    isValid = false;
                    //$('#BookPageFilePreview').hide();
                } else {
                    BookPagePdfFile.removeClass('is-invalid');
                    BookPagePdfFile.closest('.fv-row').find('.fv-plugins-message-container').empty();
                }
            }

            return isValid;
        }

        // Validate Update BookPage Form
        function validateUpdateBookPageForm(BookPageId) {
            var isValid = true;
            var maxSize = 20 * 1024 * 1024; // 20MB in Bytes
            var allowedTypes = ['image/jpeg', 'image/jpg', 'image/webp'];
            var allowedTypesPdf = ['pdf'];
            // Validate BookPage Name
            var BookPageName = $('#edit_book_name_' + BookPageId).val().trim();
            if (BookPageName === '') {
                $('#edit_book_name_' + BookPageId).addClass('is-invalid');
                //$('#errorUpdate').text('BookPage Name is required.');
                $('#edit_book_name_' + BookPageId).closest('.fv-row')
                    .find('.fv-plugins-message-container').text('BookPage Name is required.').show();
                isValid = false;
            } else {
                $('#edit_book_name_' + BookPageId).removeClass('is-invalid');
                $('#edit_book_name_' + BookPageId).closest('.fv-row')
                    .find('.fv-plugins-message-container').empty();
            }

            // Validate BookPage Description (max 500 characters)
            var BookPageDescription = $('#edit_book_description_' + BookPageId).val();
            if (BookPageDescription != '') {
                if (BookPageDescription.trim().length > 500) {
                    $('#edit_book_description_' + BookPageId).addClass('is-invalid');
                    $('#edit_book_description_' + BookPageId).closest('.fv-row')
                        .find('.fv-plugins-message-container').text('Description must not exceed 500 characters.').show();
                    isValid = false;
                } else {
                    $('#edit_book_description_' + BookPageId).removeClass('is-invalid');
                    $('#edit_book_description_' + BookPageId).closest('.fv-row')
                        .find('.fv-plugins-message-container').empty();
                }
            }



            // Validate BookPage Categories (Custom Tag Input)
            var $tagContainer = $('#edit_book_categories_' + BookPageId).closest('.tag-container');
            var tagsCount = $tagContainer.find('.tag').length;

            if (tagsCount === 0) {
                $tagContainer.addClass('is-invalid');
                $tagContainer.closest('.fv-row').find('.fv-plugins-message-container')
                    .text('At least one category is required.').show();
                isValid = false;
            } else {
                $tagContainer.removeClass('is-invalid');
                $tagContainer.closest('.fv-row').find('.fv-plugins-message-container').empty();
            }

            // Validate BookPage File (20 MB, JPEG, JPG, WebP)
            var BookPageFile = $('#edit_book_image_' + BookPageId);
            var file = BookPageFile[0].files[0];

            if (file) {
                if (file.size > maxSize) {
                    BookPageFile.addClass('is-invalid');
                    BookPageFile.closest('.fv-row').find('.fv-plugins-message-container')
                        .text('Image File should not exceed 20 MB.').show();
                    isValid = false;
                    $('#editBookPageFilePreview_' + BookPageId).hide();
                } else if (!allowedTypes.includes(file.type)) {
                    BookPageFile.addClass('is-invalid');
                    BookPageFile.closest('.fv-row').find('.fv-plugins-message-container')
                        .text('Only JPEG, JPG, and WebP file types are allowed.').show();
                    isValid = false;
                    $('#editBookPageFilePreview_' + BookPageId).hide();
                } else {
                    BookPageFile.removeClass('is-invalid');
                    BookPageFile.closest('.fv-row').find('.fv-plugins-message-container').empty();
                }
            }

            // Validate Pdf File (20 MB, JPEG, JPG, WebP)
            var BookPdfFile = $('#edit_book_pdf_' + BookPageId);
            var filepdf = BookPdfFile[0].files[0];

            if (filepdf) {
                if (filepdf.size > maxSize) {
                    BookPdfFile.addClass('is-invalid');
                    BookPdfFile.closest('.fv-row').find('.fv-plugins-message-container')
                        .text('Pdf File should not exceed 20 MB.').show();
                    isValid = false;
                    $('#editBookPageFilePdfPreview_' + BookPageId).hide();
                } else if (!allowedTypesPdf.includes(filepdf.type)) {
                    BookPdfFile.addClass('is-invalid');
                    BookPdfFile.closest('.fv-row').find('.fv-plugins-message-container')
                        .text('Only pdf file types are allowed.').show();
                    isValid = false;
                    $('#editBookPageFilePdfPreview_' + BookPageId).hide();
                } else {
                    BookPdfFile.removeClass('is-invalid');
                    BookPdfFile.closest('.fv-row').find('.fv-plugins-message-container').empty();
                }
            }

            return isValid;
        }
    </script>
        @endpush

</x-user.layout>
