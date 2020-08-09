<template>
    <div>
		<div class="row">
            <div class="col-md-12 card" style="padding:10px">
                <form @submit="update">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Website Detail</h3>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input required class="form-control" type="text" v-model="websiteDetail.name">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Slogan</label>
                                <input required class="form-control" type="text" v-model="websiteDetail.slogan">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <p>Logo on light background</p>
                            <div v-if="previewImageUrl1 != null" class="form-group">
                                <img :src="previewImageUrl1" width="100%" />
                            </div>
                            
                            <div v-if="previewImageUrl1 == null && websiteDetail.logo_1_image_name != null" class="form-group">
                                <img :src="'https://res.cloudinary.com/'+cloudinaryCloudName+'/image/upload/c_limit,h_1000,w_1000/v1/'+websiteDetail.logo_1_image_name+'.jpg'" width="100%" />
                            </div>


                            <div class="form-group">
                                <label>Image</label>
                                <input accept="image/x-png,image/gif,image/jpeg" v-on:change="changeImage1" type="file" class="form-control">
                                <div style="margin-top:10px">
                                    <div v-if="previewImageUrl1 != null && loading == false" @click="updateImage1" class="btn btn-primary">Update Image</div>
                                    <div v-if="previewImageUrl1 != null && loading" class="btn btn-primary"><i class='fa fa-circle-o-notch fa-spin'></i> Processing</div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <p>Logo on dark background</p>
                            <div v-if="previewImageUrl2 != null" class="form-group">
                                <img :src="previewImageUrl2" width="100%" />
                            </div>
                            
                            <div v-if="previewImageUrl2 == null && websiteDetail.logo_2_image_name != null" class="form-group">
                                <img :src="'https://res.cloudinary.com/'+cloudinaryCloudName+'/image/upload/c_limit,h_1000,w_1000/v1/'+websiteDetail.logo_2_image_name+'.jpg'" width="100%" />
                            </div>


                            <div class="form-group">
                                <label>Image</label>
                                <input accept="image/x-png,image/gif,image/jpeg" v-on:change="changeImage2" type="file" class="form-control">
                                <div style="margin-top:10px">
                                    <div v-if="previewImageUrl2 != null && loading == false" @click="updateImage2" class="btn btn-primary">Update Image</div>
                                    <div v-if="previewImageUrl2 != null && loading" class="btn btn-primary"><i class='fa fa-circle-o-notch fa-spin'></i> Processing</div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <h3>Colors</h3>
                        </div>

                        <div class="col-md-6">
                            <p>Color 1</p>
                            <v-swatches 
                            show-fallback 
                            v-model="websiteDetail.color_1"></v-swatches>
                        </div> 

                        <div class="col-md-6">
                            <p>Color 1 Inverted</p>
                            <v-swatches 
                            show-fallback 
                            v-model="websiteDetail.color_1_inverted"></v-swatches>
                        </div> 

                        <div class="col-md-6">
                            <p>Color 2</p>
                            <v-swatches 
                            show-fallback 
                            v-model="websiteDetail.color_2"></v-swatches>
                        </div> 

                        <div class="col-md-6">
                            <p>Color 2 Inverted</p>
                            <v-swatches 
                            show-fallback 
                            v-model="websiteDetail.color_2_inverted"></v-swatches>
                        </div>

                        <div class="col-md-6">
                            <p>Color 3</p>
                            <v-swatches 
                            show-fallback 
                            v-model="websiteDetail.color_3"></v-swatches>
                        </div> 

                        <div class="col-md-6">
                            <p>Color 3 Inverted</p>
                            <v-swatches 
                            show-fallback 
                            v-model="websiteDetail.color_3_inverted"></v-swatches>
                        </div>

                        <div class="col-md-6">
                            <p>Color 4</p>
                            <v-swatches 
                            show-fallback 
                            v-model="websiteDetail.color_4"></v-swatches>
                        </div> 

                        <div class="col-md-6">
                            <p>Color 4 Inverted</p>
                            <v-swatches 
                            show-fallback 
                            v-model="websiteDetail.color_4_inverted"></v-swatches>
                        </div>

                        <div class="col-md-12">
                            <h3>Contact Detail</h3>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Physical Address</label>
                                <input required class="form-control" type="text" v-model="websiteDetail.physical_address">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input required class="form-control" type="text" v-model="websiteDetail.contact_number">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input required class="form-control" type="text" v-model="websiteDetail.email">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Working Hours</label>
                                <input required class="form-control" type="text" v-model="websiteDetail.working_hours">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <h3>Contact Detail</h3>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Facebook Url</label>
                                <input required class="form-control" type="text" v-model="websiteDetail.facebook_url">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Instagram Url</label>
                                <input required class="form-control" type="text" v-model="websiteDetail.instagram_url">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>LinkedIn Url</label>
                                <input required class="form-control" type="text" v-model="websiteDetail.linkedin_url">
                            </div>
                        </div>
                    </div>

                    <div class="m-t-20 text-center">
                        <a href="/admin/blogs" class="btn btn-default btn-lg">Go Back</a>
                        <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                    </div>

                    
                </form>
            </div>
        </div>

        

    </div>
</template>

<script>
    
    import VSwatches from 'vue-swatches' 
    // Import the styles too, globally
    import "vue-swatches/dist/vue-swatches.css"

    export default {
        name: 'Edit-Blog',
        data(){
            return{

                loading: false,

                cloudinaryCloudName:null,

                
                successSound:new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3'),

                websiteDetail:[],

                previewImageUrl1: null,
                previewImageUrl2: null,
            }
        },
        components: { VSwatches },
        mounted() {
            const self = this;

            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;

            self.getWebsiteDetail();
        },
        methods:{

            getWebsiteDetail(){
                const self = this;


                

                axios.get('/json/websitedetail/get')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                                       
                    self.websiteDetail = data.obj;

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },

            update(e){
                const self = this;

                e.preventDefault();
        
                axios.post('/admin/websitedetail/update',{
                    websiteDetail: self.websiteDetail,
                })
                .then(response => {
                    
                    var data = response.data;

                    self.getWebsiteDetail();
                    this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Updated Page Successfully',
                        onOpen: function() {
                            var zippi = new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3')
                            zippi.play();
                        }
                    });
                    //self.successSound.play();
                    self.getGalleryFiles();
                })
                .catch(error => {
                    Swal.showValidationMessage(
                    `Request failed: ${error}`
                    )
                })

            },  


            changeImage1(e){
                const self = this;

                var files = e.target.files || e.dataTransfer.files;
                if (!files.length){
                    return;
                }
                    
                self.logo_1_image_name = files[0];
                self.previewImageUrl1 =  URL.createObjectURL(self.logo_1_image_name);
                //console.log(self.image_name);
            },

            updateImage1(){
                const self = this;
                
                self.loading = true;

                var formData = new FormData();
                formData.append('logo_1_image_name', self.logo_1_image_name); 

                axios.post('/admin/websitedetail/updatepageimage1', 
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                
                })
                .then(response => {
                    self.loading = false;

                    //self.websiteDetail.logo_1_image_name = null;
                    self.previewImageUrl =  null;

                    self.getWebsiteDetail();
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )

                    console.log(error);
                    alert('Something went wrong with uploads');
                    
                    self.loading = false;
                })
                
                //console.log(self.galleryFiles);
            },

            changeImage2(e){
                const self = this;

                var files = e.target.files || e.dataTransfer.files;
                if (!files.length){
                    return;
                }
                    
                self.logo_2_image_name = files[0];
                self.previewImageUrl2 =  URL.createObjectURL(self.logo_2_image_name);
                //console.log(self.image_name);
            },

            updateImage2(){
                const self = this;
                
                self.loading = true;

                var formData = new FormData();
                formData.append('logo_2_image_name', self.logo_2_image_name); 

                axios.post('/admin/websitedetail/updatepageimage2', 
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                
                })
                .then(response => {
                    self.loading = false;

                    //self.websiteDetail.logo_2_image_name = null;
                    self.previewImageUrl2 =  null;

                    self.getWebsiteDetail();
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )

                    alert('Something went wrong with uploads');
                    
                    self.loading = false;
                })
                
                //console.log(self.galleryFiles);
            },
            
            
            
        }

    }

</script>