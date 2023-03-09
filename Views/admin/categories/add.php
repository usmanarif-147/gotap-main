<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Category</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Category</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Category</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="POST" action="#" enctype="multipart/form-data">
			  <?php csrf()?>
                <div class="card-body">
                  <div class="form-group">
                    <label >Title (English)</label>
                    <input type="text" minlength="1" maxlength="32"  class="form-control" name="name" placeholder="Enter category title" required>
                  </div>
				   <div class="form-group">
                    <label>Title (Swedish)</label>
                    <input type="text" maxlength="32" class="form-control" name="name_sv" placeholder="Ange kategorititel" required>
                  </div>
                
				  
                   <div class="form-group">
                       <label>Select Status</label>
                       <select class="custom-select" name="status" required>
                         <option value="1" >Active</option>
                         <option value="0">Inactive</option>
                       </select>
                   </div>
                 
     
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

          </div>
          <!--/.col (left) -->
          
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<script src="../assets/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>