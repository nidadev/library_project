<style>
    .img_src
    {
        width:20%;
    }
    </style>
    <x-admin.layout pageTitle="Borrow Page">
<div class="row gy-5">
    <!-- States -->




    <!-- Books  -->
    <div class="col-md-12 mt-3">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title fs-1">Borrow Request</h3>
            </div>
            <div class="px-3 py-1">
                @if ($borrow->isEmpty())
                    <x-empty-state title="No Borrow request" message="No Borrow request available at the moment." />
                @else
                    <table class="table table-striped" id="recommendedJobsTable">
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Book Cover Image</th>
                                <th>Auther</th>
                                <th>Release Year</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($borrow as $br)
                                <tr>
                                    <td>{{ $br->book->name }}</td>
                                    <td><img class="img_src" src="{{ asset('storage/' . $br->book->file_path) }}" alt=""> </td>

                                    <td>{{ $br->user->name }}</td>

                                    <td>{{ $br->book->release_year }}</td>
                                    <td>{{ $br->book->description }}</td>

                                    <td>
                                        <div class="text-button">
                                            <button class="btn btn-success" href="#"  data-bs-toggle="modal"
                                            data-bs-target="#approveModal_{{ $br->id }}" >Approve</button>
                                          </div>

                                          <div class="text-button">
                                            <button class="btn btn-danger" href="#"  data-bs-toggle="modal"
                                            data-bs-target="#deleteModal2_{{ $br->id }}" >Reject</button>
                                          </div>

                                    </td>
                                </tr>
  <!-- Delete Modal -->
                                            <div class="modal fade" id="approveModal_{{ $br->id }}" tabindex="-1"
                                                aria-labelledby="statusModalLabel" aria-hidden="true"
                                                data-bs-backdrop="static">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Approve</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to approve
                                                            <strong>{{ $br->book->title }}</strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <form
                                                                action="{{ route('admin.bookpage.borrow.store', $br->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger">
                                                                    Yes,Approve
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>


</div>
    </x-admin.layout>
