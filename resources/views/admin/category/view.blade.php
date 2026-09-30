    @include('admin.include.header')
        <div class="body-wrapper-inner">
            <div class="container-fluid">
            <!--  Row 1 -->
                <div class="row">
                    <div class="col-lg-12 d-flex align-items-strech">              
                        <div class="card card-body py-3">
                            <div class="row align-items-center">
                            <div class="col-12">
                                <div class="d-sm-flex align-items-center justify-space-between">
                                <h4 class="mb-4 mb-sm-0 card-title">View Category</h4>
                                <nav aria-label="breadcrumb" class="ms-auto">
                                    <ol class="breadcrumb">
                                    <li class="breadcrumb-item d-flex align-items-center">
                                        <a class="text-muted text-decoration-none d-flex" >
                                        <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">
                                        Category
                                        </span>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">
                                        View
                                        </span>
                                    </li>
                                    </ol>
                                </nav>
                                </div>
                            </div>
                            </div>
                        </div> 
                    </div>           
                </div>  

            @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                      @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
                <div class="row">
                    <div class="col-12">
                        <!-- ----------------------------------------- -->
                        <!-- 1. Basic Form -->
                        <!-- ----------------------------------------- -->
                        <!-- start Basic Form -->
                        <div class="card">
                        <div class="card-body">
                            <!-- <h4 class="card-title mb-3">Basic Form</h4> -->
                            <table id="myTable" class="table text-nowrap mb-0 align-middle">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">#</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Name</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Image</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-4 fw-semibold mb-0">Slug</h6>
                                        </th>
                                        <th style="text-align:right">
                                            <h6 class="fs-4 fw-semibold mb-0">Action</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category as $data)
                                    <tr>
                                        <td>
                                            <h6 class="fw-semibold mb-0">{{ $loop->index+1 }}</h6>
                                        </td>
                                        <td>
                                            {{ $data->name }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ URL::to('') }}/{{$data->image}}" class="rounded-circle" width="40" height="40">
                                                
                                            </div>
                                        </td>
                                        <td>
                                            {{ $data->slug }}
                                        </td>
                                        <td style="float:right">  
                                            <div class="align-items-center gap-3">
                                                <a href="{{ route('editcategory', $data->id) }}">
                                                    <span class="badge bg-warning-subtle text-warning d-inline-flex align-items-center gap-1"><i class="fs-4 ti ti-edit"></i>Edit</span>
                                                </a>
                                                <a data-bs-toggle="modal" data-bs-target="#deleteCategoryModal{{ $data->id }}" style="cursor:pointer">
                                                    <span class="badge bg-danger-subtle text-danger d-inline-flex align-items-center gap-1"><i class="fs-4 ti ti-trash"></i>Delete</span>
                                                </a>    
                                            </div>                                                  
                                        </td>
                                    </tr>
                                   <div class="modal fade" id="deleteCategoryModal{{ $data->id }}" tabindex="-1" aria-labelledby="deleteCategoryLabel{{ $data->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteCategoryLabel">Delete Category</h5>
                                                <hr>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h3>Are you sure you want to permanently delete this category?</h3>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                        <a href ="{{ route('deletecategory', $data->id) }}" id="confirmDeleteBtn" class="btn btn-primary"> Delete </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                   
                                    @endforeach
                                </tbody>
                            </table>
  
                        </div>
                        </div>
                        <!-- end Basic Form -->
                    </div> 
 
            </div>
        </div>
    @include('admin.include.footer')