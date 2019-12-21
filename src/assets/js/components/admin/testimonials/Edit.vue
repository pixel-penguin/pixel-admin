<template>
    <div>
		<div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form @submit="updateTestimonial">
                    
                    
                    <div v-if="testimonial_id > 0">
                        <div v-if="previewImageUrl != null" class="form-group">
                            <img :src="previewImageUrl" width="100%" />
                        </div>
                        
                        <div v-if="previewImageUrl == null && testimonial.image_name != null" class="form-group">
                            <img :src="'https://res.cloudinary.com/'+cloudinaryCloudName+'/image/upload/c_limit,h_1000,w_1000/v1/'+testimonial.image_name+'.jpg'" width="100%" />
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input accept="image/x-png,image/gif,image/jpeg" v-on:change="changeImage" type="file" class="form-control" required>
                            <div style="margin-top:10px">
                                <div v-if="previewImageUrl != null && loading == false" @click="updateImage" class="btn btn-primary">Update Image</div>
                                <div v-if="previewImageUrl != null && loading" class="btn btn-primary"><i class='fa fa-circle-o-notch fa-spin'></i> Processing</div>
                            </div>

                        </div>
                    </div>

                    
                    
                    <div class="form-group">
                        <label>Name</label>
                        <input required class="form-control" type="text" v-model="testimonial.name">
                    </div>

                    <div class="form-group">
                        <label>Position</label>
                        <input required class="form-control" type="text" v-model="testimonial.position">
                    </div>

                    <div class="form-group">
                        <label>Message</label>
                        <textarea required class="form-control" type="text" v-model="testimonial.detail"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Active</label>
                        <input type="checkbox" v-model="testimonial.active">
                    </div>
                    

                    <div class="m-t-20 text-center">
                        <a href="/admin/testimonials" class="btn btn-default btn-lg">Go Back</a>
                        <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                    </div>

                    
                </form>
            </div>
        </div>

    </div>
</template>

<script>

    export default {
        name: 'Edit-Testimonial',
        data(){
            return{
                cloudinaryCloudName:null,

                loading: false,
                previewImageUrl:null,
                testimonial_id: 0,
                testimonial:{
                    id:0,
                    image_name: null,
                    name: null,
                    position: null,
                    detail:null,
                    active: false,
                },
                successSound:new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3')
            }
        },
        props: ['testimonial_id_link'],
        mounted() {
            const self = this;

            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;

            self.testimonial_id = self.testimonial_id_link;
            
            if(self.testimonial_id > 0){
                self.testimonialDetail();
            }
        },
        methods:{
            changeImage(e){
                const self = this;

                var files = e.target.files || e.dataTransfer.files;
                if (!files.length){
                    return;
                }
                    
                self.testimonial.image_name = files[0];
                self.previewImageUrl =  URL.createObjectURL(self.testimonial.image_name);
                //console.log(self.image_name);
            },
            testimonialDetail(){
                const self = this;

                axios.get('/json/testimonial/detail/'+self.testimonial_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.testimonial = data.obj;

                    self.testimonial_id = self.testimonial.id;

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            updateImage(){
                const self = this;
                
                self.loading = true;

                var formData = new FormData();
                formData.append('testimonial_id', self.testimonial_id);
                formData.append('image_name', self.testimonial.image_name); 

                axios.post('/admin/testimonials/updateimage', 
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                
                })
                .then(response => {
                    self.loading = false;

                    self.testimonial.image_name = null;
                    self.previewImageUrl =  null;

                    self.testimonialDetail();
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
            updateTestimonial(e){
                const self = this;

                e.preventDefault();
        
                axios.patch('/admin/testimonials/'+self.testimonial_id,{
                    name: self.testimonial.name,
                    position: self.testimonial.position,
                    active: self.testimonial.active,
                    detail: self.testimonial.detail
                })
                .then(response => {
                    
                    var data = response.data;

                    self.testimonial_id = data.obj.id;

                    self.testimonialDetail();
                    this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Updated Page Successfully'
                    });
                    self.successSound.play();
                })
                .catch(error => {
                    Swal.showValidationMessage(
                    `Request failed: ${error}`
                    )
                })

            },           
            
        }

    }

</script>
