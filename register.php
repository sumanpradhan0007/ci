<div class="row page-content">
    <div class="col-lg-12">
        <h2>Register Form</h2>
        <?php if (validation_errors()) { ?>
            <div class="alert alert-danger">
                <?php echo validation_errors(); ?>
            </div>
        <?php } ?>
        <?php echo form_open('auth/actionCreate'); ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-user-o"></i>
                        </span>
                        <input type="text" name="first_name" class="form-control" id="first-name" placeholder="First Name">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-user-o"></i>
                        </span>
                        <input type="text" name="last_name" class="form-control" id="last-name" placeholder="Last Name">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">                 
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-envelope"></i>
                        </span>
                        <input type="text" name="email" class="form-control" id="email" placeholder="Email">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-phone"></i>
                        </span>
                        <input type="text" name="contact_no" class="form-control" id="contact-no" placeholder="Contact No">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    </div>
                </div>
            </div>      
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-lock"></i>
                        </span>
                        <input type="password" name="confirm_password" class="form-control" id="confirm-password" placeholder="Confirm Password">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-map-marker"></i>
                        </span>
                        <input type="text" name="address" class="form-control" id="address" placeholder="Address">
                    </div>
                </div>
            </div>      
            <div class='col-lg-6'>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type='text' class="form-control" name="dob" id="dob" placeholder="DOB: DD-MM-YYYY">                    
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group pull-right">                    
                    <span class="small ">Already Registered User?</span> <a href="<?php print site_url(); ?>signin" class="small">Click here to login</a>
                </div>
            </div>                
        </div>
        <div class="row"> 
            <div class="col-lg-12">
                <div class="form-group pull-right">
                    <button type="submit" id="register" class="btn btn-info">Register</button>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?> 
</div>
