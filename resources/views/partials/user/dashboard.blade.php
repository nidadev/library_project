<style>
    .img_src
    {
        width:80%;
    }
    </style>
<div class="row gy-5">
    <!-- States -->


    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-header align-items-center justify-content-center">
                <h5 class="fs-1 fw-bolder">Borrowed Books </h5>
            </div>
            <div class="card-body">
                <h2 class="display-3"></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-header align-items-center justify-content-center">
                <h5 class="fs-1 fw-bolder">Wish List</h5>
            </div>
            <div class="card-body">
                <h2 class="display-3">{{ $totalUsers }}</h2>
            </div>
        </div>
    </div>
    <!-- Job Applications -->
    <div class="col-md-12 mt-3">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title fs-1">View Available Books</h3>
            </div>
            <div class="px-3 py-1">
                @if ($bookData->isEmpty())
                <?php //dd($bookData);?>
                    <x-empty-state title="No books page" message="You have no books yet." />
                @else
                    <table class="table table-striped" id="appliedJobsTable">
                        <thead>
                            <tr>
                                <!--th>Title</th>
                                <th>Description</th>
                                <th>Applied Date</th>
                                <th>Action</th-->
                            </tr>
                        </thead>
                        <tbody>
                            <div class="row gy-5">
                                @foreach ($bookData as $bk)
                                <div class="col-md-3">
                                    <div class="card text-center">
                                        <div class="card-header align-items-center justify-content-center">
                                            <h5 class="fs-1 fw-bolder">{{ $bk->name }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <h2 class="display-3"></h2>
                                            <div class="item">
                                                <div class="left-image">
                                                  <img class="img_src" src="{{ asset('storage/' . $bk->file_path) }}" alt="" style="border-radius: 20px; min-width: 195px;">
                                                </div>
                                                <div class="right-content">
                                                  <span class="author">
                                                    <img src="assets/images/author.jpg" alt="" style="max-width: 50px; border-radius: 50%;">
                                                    <h6>{{$bk->user->name }}</h6>
                                                  </span>
                                                  <div class="line-dec"></div>
                                                  <span class="bid">
                                                    Current Available<br><strong>{{$bk->quantity }}</strong><br>
                                                  </span>
                                                  <!--span class="ends">
                                                    Total<br><strong>20</strong><br>
                                                  </span-->
                                                  <div class="text-button">
                                                    <a href="details.html">View Item Details</a>
                                                  </div>

                                                  <div class="text-button">
                                                    <button class="btn btn-success" href="#"  data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal_{{ $bk->id }}" >Borrow Request</button>
                                                  </div>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>

<!----------- Borrow Request -->
<div class="modal fade" id="deleteModal_{{ $bk->id }}" tabindex="-1"
    aria-labelledby="statusModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Borrow Request</h5>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to borrow this book
                <strong>{{ $bk->name }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Cancel</button>
                <form
                    action="{{ route('user.bookpage.borrow.store', $bk->id) }}"
                    method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        Yes,Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--------- ---------->



                                @endforeach
                            </div><!-- row--->

                                <!--tr>
                                    <td>
                                        <span class="author">
                                            <img src="" alt="" style="max-width: 50px; border-radius: 50%;">
                                            <h6>Robert T Kiyosaki</h6>
                                          </span>
                                    </td>
                                    <td>{{$bk->description}}</td>
                                    <td>{{$bk->created_at->format('d M Y')}}</td>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-primary view-job" data-id="{{ $bk->id }}"
                                            data-bs-toggle="modal" data-bs-target="#jobDetailModal">
                                            <i class="fas fa-eye me-1"></i> View
                                        </button>
                                    </td>
                                </tr-->
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>

    <!-- Books  -->
    {{-- <div class="col-md-12 mt-3">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title fs-1">Recently added books</h3>
            </div>
            <div class="px-3 py-1">
                @if ($bookData->isEmpty())
                    <x-empty-state title="No Recommended Jobs" message="No recommended jobs available at the moment." />
                @else
                    <table class="table table-striped" id="recommendedJobsTable">
                        <thead>
                            <tr>
                                <th> Title</th>
                                <th>User</th>
                                <th>Genres</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookData as $bk)
                                <tr>
                                    <td>{{ $bk->name }}</td>
                                    <td>{{ $bk->name }}</td>
                                    <td>{{ $bk->description }}</td>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-primary view-job" data-id="{{ $bk->id }}"
                                            data-bs-toggle="modal" data-bs-target="#jobDetailModal">
                                            <i class="fas fa-eye me-1"></i> View
                                        </button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div> --}}


</div>
