<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>General Form</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">General Form</li>
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
                <h3 class="card-title">Add New Platform </h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="POST" action="#" enctype="multipart/form-data">
			          <?php csrf()?>
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputTitle">Title</label>
                    <input type="text" class="form-control" name="title" id="exampleInputTitle" placeholder="Enter Title" required>
                  </div>
                
				  <div class="row">
                    <div class="col-sm-6">
                       <div class="form-group">
							<label for="exampleInputFile">Icon</label>
							<div class="input-group">
							  <div class="custom-file">
								<input type="file" class="custom-file-input" name="icon" id="exampleInputFile" required>
								<label class="custom-file-label" for="exampleInputFile">Choose file</label>
							  </div>
							  <!--<div class="input-group-append">
								<span class="input-group-text" id="">Upload</span>
							  </div>-->
							</div>
						</div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Select type</label>
                        <select class="custom-select" name="pro" required>
                          <option value="1">Pro</option>
                          <option value="0" selected>Free</option>
                        </select>
                      </div>
                    </div>
                  </div>
				  
				  
				  <div class="row">
                    <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
                        <label>Select Category</label>
                        <select class="custom-select" name="category_id" required>
                            <?php foreach($categories as $category){ ?>
                         <option  value="<?=$category ->id ?>"><?=$category ->name ?></option>
						            <?php } ?>
              
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Select Status</label>
                        <select class="custom-select" name="status" required>
                          <option value="1">Active</option>
                          <option value="0">Inactive</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
						<label>Placeholder (English)</label>
						<input type="text" class="form-control" minlength="3" maxlength="48"  name="placeholder_en" placeholder="Enter Placeholder for this platform">
					  </div>
                    </div>
                     <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
						<label>Placeholder (Swedish)</label>
						<input type="text" class="form-control"  minlength="3"  maxlength="48" name="placeholder_sv" placeholder="Ange platshållare för denna plattform">
					  </div>
                    </div>
                  </div>
                  
                  
                  <div class="row">
                    <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
						<label>Description (English)</label>
						<input type="text" class="form-control" minlength="3" maxlength="191"  name="description_en" placeholder="Enter Description of this platform">
					  </div>
                    </div>
                     <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
						<label>Description (Swedish)</label>
						<input type="text" class="form-control"  minlength="3"  maxlength="191" name="description_sv" placeholder="Ange beskrivning av denna plattform">
					  </div>
                    </div>
                  </div>
				  
				  
				  
				  <div class="row">
                    <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
						<label for="exampleInputurl">Base URL</label>
						<input type="text" class="form-control" name="baseURL" id="exampleInputurl" placeholder="Enter Base URL i.e https://facebook.com/">
					  </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Input Type</label>
                        <select class="custom-select" name="input" required>
                          <option value="username">Username</option>
                          <option value="email">Email</option>
						  <option value="phone">Phone</option>
                          <option value="url">URL</option>
						  <option value="other">Other</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <!--<div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                  </div>-->
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <span  class="text-danger ml-4"><?= $errors[0] ?? ''?></span> 
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
