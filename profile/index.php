<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">

                <img id="img"
                     class="avatar img-circle img-thumbnail"
                     alt="avatar"><span class="font-weight-bold"></span><span
                    class="text-black-50"></span><span> </span></div>
            <input class="text-center center-block file-upload" name="photo" type="file" id="imgs" accept="image/*"
                   onchange="document.getElementById('img').src = window.URL.createObjectURL(this.files[0])">
            <div class="mt-5 text-center">
            </div>
        </div>

        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile Settings</h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">Name</label><input type="text" id="first_name"
                                                                                   name="first_name"
                                                                                   class="form-control"
                                                                                   placeholder="first name" value="">
                    </div>
                    <div class="col-md-6"><label class="labels">Surname</label><input type="text" id="last_name"
                                                                                      name="last_name"
                                                                                      class="form-control"
                                                                                      value="" placeholder="surname">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Mobile Number</label><input type="text" id="phone"
                                                                                             name="phone"
                                                                                             class="form-control"
                                                                                             placeholder="enter phone number"
                                                                                             value=""></div>
                    <div class="col-md-12"><label class="labels">Email</label><input type="text" id="email" name="email"
                                                                                     class="form-control"
                                                                                     placeholder="enter email id"
                                                                                     value=""></div>
                </div>
                <div class="row mt-3">
                </div>
                <div class="mt-5 text-center">
                    <button class="btn btn-primary profile-button" onclick="updateProfile()" type="button">Save
                        Profile
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience"><span>Edit Password</span>
                </div>
                <br>
                <div class="col-md-12"><label class="labels">Old Password</label><input type="password"
                                                                                        class="form-control"
                                                                                        placeholder="old password"
                                                                                        id="oldpass" name="oldpass"
                                                                                        value="">
                </div>
                <br>
                <div class="col-md-12"><label class="labels">New Password</label><input type="password" id="newpass"
                                                                                        name="newpass"
                                                                                        class="form-control"
                                                                                        placeholder="password" value="">
                </div>
                <br>
                <div class="col-md-12"><label class="labels">Verify</label><input type="password" id="verifypass"
                                                                                  name="verifypass" class="form-control"
                                                                                  placeholder="Verify Password"
                                                                                  value=""></div>
                <br>
                <button class="btn btn-primary profile-button" onclick="updatepassword()" type="button">Update
                    Password
                </button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php require_once "script.php"?>