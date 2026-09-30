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
                                <h4 class="mb-4 mb-sm-0 card-title">Order: #1703</h4>
                                <nav aria-label="breadcrumb" class="ms-auto">
                                    <ol class="breadcrumb">
                                    <li class="breadcrumb-item d-flex align-items-center">
                                        <a class="text-muted text-decoration-none d-flex" >
                                        <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">
                                        Order
                                        </span>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">
                                        Detail
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


                <div class="row">
                    <div class="col-12">
                        <!-- ----------------------------------------- -->
                        <!-- 1. Basic Form -->
                        <!-- ----------------------------------------- -->
                        <!-- start Basic Form -->
                        <div class="card">
                        <div class="card-body">
                            <!-- <h4 class="card-title mb-3">Basic Form</h4> -->
                            <form action="" method="POST">
                                @csrf
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="name" >
                                            <label >Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="email">
                                            <label >Email</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="contry">
                                            <label >Country / Region</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="address">
                                            <label >Street address</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="city">
                                            <label >Town / City</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="state">
                                            <label >State / County</label>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="postcode">
                                            <label >Postcode / ZIP</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="phone">
                                            <label >Phone</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" name="date">
                                        <label >Date</label>
                                    </div>
                                    </div>
                                    {{-- <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="product">
                                            <label >Product</label>
                                        </div>
                                    </div>
                                      <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="subtotal">
                                            <label >Subtotal</label>
                                        </div>
                                    </div>
                                     <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="quantity">
                                            <label >Quantity</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="charges">
                                            <label >Delivery Charges</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" name="price" >
                                        <label for="tb-fname">Price</label>
                                    </div>
                                    </div> --}}
                                     <div class="col-md-12">
                            <div class="table-responsive mt-5">
                              <table class="table table-hover">
                                <thead>
                                  <!-- start row -->
                                  <tr>
                                    <th class="text-center">#</th>
                                    <th>Product</th>
                                    <th class="text-end">Quantity</th>
                                    <th class="text-end">Price</th>
                                  </tr>
                                  <!-- end row -->
                                </thead>
                                <tbody>
                                  <!-- start row -->
                                  <tr>
                                    <td class="text-center">1</td>
                                    <td>Milk Powder</td>
                                    <td class="text-end">5</td>
                                    <td class="text-end">108</td>
                                  </tr>
                                  <!-- end row -->
                                  <!-- start row -->
                                  <tr>
                                    <td class="text-center">2</td>
                                    <td>Air Conditioner</td>
                                    <td class="text-end">5</td>
                                    <td class="text-end">108</td>
                                  </tr>
                                  <!-- end row -->
                                  <!-- start row -->
                                  <tr>
                                    <td class="text-center">3</td>
                                    <td>RC Cars</td>
                                    <td class="text-end">5</td>
                                    <td class="text-end">108</td>
                                  </tr>
                                  <!-- end row -->
                                  <!-- start row -->
                                  <tr>
                                    <td class="text-center">4</td>
                                    <td>Down Coat</td>
                                    <td class="text-end">5</td>
                                    <td class="text-end">108</td>
                                  </tr>
                                  <!-- end row -->
                                </tbody>
                              </table>
                            </div>
                          </div>
                                </div>
                            </form>
                             <div class="col-md-12">
                            <div class="pull-right mt-4 text-end">
                              <p> Sub-total: $20,858</p>
                             <p>Delivery Charges : $20</p>
                             <p>Discount [disc7]:  -$120 </p>
                              <hr />
                              <h3>
                                <b>Total :</b> $22,943
                              </h3>
                            </div>
                            <div class="clearfix"></div>
                            <hr />
                           
                        
                        </div>
                        <!-- end Basic Form -->
                        
                    </div> 
 
            </div>
        </div>
    @include('admin.include.footer')