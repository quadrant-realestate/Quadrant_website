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
                                <h4 class="mb-4 mb-sm-0 card-title">Edit Category</h4>
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
                                        Edit
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

                                    @if ($errors->any())
                                        <div class="alert alert-danger text-center">
                                        Please input the required fields.
                                        </div>
                                    @endif
                                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                                    {{ session('success') }}
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
                            <form action="{{ route('updatecategory', $category->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="name" placeholder="Enter Name here" value="{{ $category->name }}" id="category_name" oninput="generateSlug()">
                                        <label for="tb-fname">Name *</label>
                                        @if ($errors->has('name'))
                                            <span class="text-danger text-sm ml-2">This Field is required.</span>
                                            @endif
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="slug" value="{{ $category->slug }}" placeholder="Slug" id="category_slug">
                                        <label >Slug *</label>
                                        @if ($errors->has('slug'))
                                            <span class="text-danger text-sm ml-2">This Field is required.</span>
                                        @endif
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="meta_title" value="{{ $category->meta_title }}" placeholder="Meta Title">
                                        <label >Meta Title *</label>
                                        @if ($errors->has('meta_title'))
                                            <span class="text-danger text-sm ml-2">This Field is required.</span>
                                        @endif
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="meta_description" value="{{ $category->meta_description }}"  placeholder="Meta Description">
                                            <label >Meta Description *</label>
                                            @if ($errors->has('meta_description'))
                                                <span class="text-danger text-sm ml-2">This Field is required.</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">                                          
                                        <label for="formFileMultiple" class="form-label">Category Image *</label>
                                        @if ($errors->has('image'))
                                            <span class="text-danger text-sm ml-2">This Field is required.</span>
                                        @endif
                                        <input class="form-control" type="file" name="image" >  
                                            <div class="mb-2 mt-3">
                                                <img src="{{ URL::to('') }}/{{ $category->image }}" alt="Current Category Image" width="100" height="100" class="rounded border">
                                            </div>                                              
                                    </div>
                                    <div class="col-12">
                                    <div class="d-md-flex align-items-center">                                        
                                        <div class="ms-auto mt-3 mt-md-0">
                                        <button type="submit" class="btn btn-primary hstack gap-6">
                                            <i class="ti ti-send fs-4"></i> Submit </button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                        <!-- end Basic Form -->
                    </div> 
 
            </div>
        </div>
    @include('admin.include.footer')

    <script>
    function generateSlug() {
        const name = document.getElementById('category_name').value;
        const slug = name.trim().toLowerCase().toString()                  // Convert to string
        .trim()                      // Remove whitespace from both ends
        .toLowerCase()               // Convert to lowercase
        .replace(/&/g, '-and-')      // Replace & with 'and'
        .replace(/[\s\W-]+/g, '-')   // Replace spaces, non-word characters, and dashes with a single dash
        .replace(/^-+|-+$/g, '');;
        document.getElementById('category_slug').value = slug;
    }
</script>