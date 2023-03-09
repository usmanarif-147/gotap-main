<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Category</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Category</li>
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
                <h3 class="card-title">Edit Category</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="POST" action="#" enctype="multipart/form-data">
			  <?php csrf()?>
			  <input type="hidden" name="id" value="<?=$category ->id ?>">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputTitle">Title (English)</label>
                    <input type="text" value="<?=$category ->name ?>" class="form-control" name="name" id="exampleInputTitle" placeholder="Enter Title" required>
                  </div>
				   <div class="form-group">
                    <label for="exampleInputTitleSV">Title (Swedish)</label>
                    <input type="text" value="<?=$category ->name_sv ?>" class="form-control" name="name_sv" id="exampleInputTitleSV" placeholder="Enter Title" required>
                  </div>
                
				 
				  
				  
				  
                      <div class="form-group">
                        <label>Select Status</label>
                        <select class="custom-select" name="status" required>
                          <option value="1" <?=$category ->status ==1 ? 'selected' : ''?>>Active</option>
                          <option value="0" <?=$category ->status ==0 ? 'selected' : ''?>>Inactive</option>
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