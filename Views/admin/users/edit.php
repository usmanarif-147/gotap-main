<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit User</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit User</li>
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
              <h3 class="card-title">Quick Example</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="#" enctype="multipart/form-data">
              <?php csrf() ?>
              <input type="hidden" name="id" value="<?= $user->id ?>">
              <div class="card-body">

                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="exampleInputTitle">Name</label>
                      <input type="text" value="<?= $user->name ?>" class="form-control" name="name" id="exampleInputName" placeholder="Enter Name" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="exampleInputEmail">Email</label>
                      <input type="email" value="<?= $user->email ?>" class="form-control" name="email" id="exampleInputEmail" placeholder="Enter Email" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="exampleInputFile">Photo</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="photo" id="exampleInputFile">
                          <label class="custom-file-label" for="exampleInputFile">Choose photo</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="exampleInputFile">Wallpaper Photo</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="cover_photo" id="exampleInputFile">
                          <label class="custom-file-label" for="exampleInputFile">Choose photo</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="exampleInputUsername">Username</label>
                      <input type="username" value="<?= $user->username ?>" class="form-control" name="username" id="exampleInputUsername" placeholder="Enter Username" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="exampleInputPhone">Phone</label>
                      <input type="phone" value="<?= $user->phone ?>" class="form-control" name="phone" id="exampleInputPhone" placeholder="Enter Phone Number" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="exampleInputUsername">Job title</label>
                      <input type="username" value="<?= $user->job_title ?>" class="form-control" name="job_title" id="exampleInputUsername" placeholder="Enter Job Title">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="exampleInputPhone">Company</label>
                      <input type="phone" value="<?= $user->company ?>" class="form-control" name="company" id="exampleInputPhone" placeholder="Enter Company">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Bio</label>
                      <textarea class="form-control" name="bio" id="" cols="30" rows="3"><?= $user->bio ? $user->bio : '' ?></textarea>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Verified</label>
                      <select class="custom-select" name="verified" required>
                        <option value="1" <?= $user->verified == 1 ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= $user->verified == 0 ? 'selected' : '' ?>>No</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Show In All Users</label>
                      <select class="custom-select" name=" featured" required>
                        <option value="1" <?= $user->featured == 1 ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= $user->featured == 0 ? 'selected' : '' ?>>No</option>
                      </select>
                    </div>
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
            <span class="text-danger ml-3"><?= $error ?></span>
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
  $(document).ready(function() {
    bsCustomFileInput.init();
  });
</script>