<x-admin.layout pageTitle="Book Page">
<div class="container">
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Book Page</h3>
                    <div class="card-toolbar">
                      
                        <button class="btn btn-primary text-white" data-bs-toggle="modal"
                            data-bs-target="#addCertificateModal">Add Books</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="certificate_table">
                        <thead>
                            <tr>
                                <th>Books Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Certificate Modal -->
    <div class="modal fade" id="addCertificateModal" tabindex="-1" aria-labelledby="addCertificateModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCertificateModalLabel">Add Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="addCertificateForm">
                        @csrf
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label for="certificate_name" class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Name</span>
                            </label>
                            <!--end::Label-->

                            <input type="text" class="form-control" placeholder="Enter Certificate Name"
                                name="certificate_name" id="certificate_name">
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            </div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row g-9 mb-8">
                            <!--begin::Col-->
                            <div class="col-md-12 fv-row">
                                <label class="fs-6 fw-semibold mb-2" for="certificate_description">Description</label>
                                <!--begin::Input-->
                                <div class="position-relative d-flex align-items-center">
                                    <textarea class="form-control" name="certificate_description" id="certificate_description"></textarea>
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->


                        <!--begin::Actions-->
                        <div class="text-center">
                            <button id="createCertificateBtn" class="btn btn-primary text-white">
                                <span class="indicator-label">
                                    Add Book
                                </span>
                                <span class="indicator-progress">
                                    Please wait... <span
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin.layout>
