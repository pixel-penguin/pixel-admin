<template>
    <div>
		 <div class="row" style="margin-bottom:10px">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4" style="padding-left:0">
                        <a href="/admin/specials" class="btn btn-white"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
                    </div>
                </div>
            </div>
        </div>

		<div class="row">
            <div class="col-md-12 card" style="padding:10px">
                <form @submit="updateSpecial">
                    
                    
                    <div v-if="special_id > 0">
                        <div v-if="previewImageUrl != null" class="form-group">
                            <img :src="previewImageUrl" width="100%" />
                        </div>
                        
                        <div v-if="previewImageUrl == null && special.image_name != null" class="form-group">
                            <img :src="'https://res.cloudinary.com/'+cloudinaryCloudName+'/image/upload/c_limit,h_1000,w_1000/v1/'+special.image_name+'.jpg'" width="100%" />
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input accept="image/x-png,image/gif,image/jpeg" v-on:change="changeImage" type="file" class="form-control" >
                            <div style="margin-top:10px">
                                <div v-if="previewImageUrl != null && loading == false" @click="updateImage" class="btn btn-primary">Update Image</div>
                                <div v-if="previewImageUrl != null && loading" class="btn btn-primary"><i class='fa fa-circle-o-notch fa-spin'></i> Processing</div>
                            </div>

                        </div>
                    </div>

                    
                    
                    <div class="form-group">
                        <label>Name</label>
                        <input required class="form-control" type="text" v-model="special.name">
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <input required class="form-control" type="text" v-model="special.price">
                    </div>

                    <div class="form-group">
                        <label>Url</label>
                        <input class="form-control" type="text" v-model="special.url">
                    </div>

                    <div class="form-group">
                        <label>Time</label>
                        <input required class="form-control" type="text" v-model="special.time">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                       
                        <editor 
                        v-model="special.detail"
                        api-key="9tv5nzovtpredmv4b778na7pauhqz7n3rk4pviia6wla45v2" 
                        :init="{
                            browser_spellcheck: true,
                            plugins: 'wordcount, image, media, link, table, lists, code', 
                            //menubar: 'insert',
                            toolbar: 'image, media, link, table, numlist bullist, code',
                            file_picker_types: 'file image media',
                            //extended_valid_elements: 'svg[*],defs[*],pattern[*],desc[*],metadata[*],g[*],mask[*],path[*],line[*],marker[*],rect[*],circle[*],ellipse[*],polygon[*],polyline[*],linearGradient[*],radialGradient[*],stop[*],image[*],view[*],text[*],textPath[*],title[*],tspan[*],glyph[*],symbol[*],switch[*],use[*]',
                            file_browser_callback_types: 'file image media',
                            automatic_uploads: true,
                            images_upload_url: '/admin/mcefileupload',
                            height: 300,        
                            
                        }">   <textarea></textarea>
                        </editor>

                    </div>

                    <div class="form-group">
                        <label>Active</label>
                        <input type="checkbox" v-model="special.active">
                    </div>
                    

                    <div class="m-t-20 text-center">
                        <a href="/admin/specials" class="btn btn-default btn-lg">Go Back</a>
                        <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                    </div>

                    
                </form>
            </div>
        </div>

    </div>
</template>

<script>

    import Editor from '@tinymce/tinymce-vue';

    export default {
        name: 'Edit-Special',
        data(){
            return{
                cloudinaryCloudName:null,

                loading: false,
                previewImageUrl:null,
                special_id: 0,
                special:{
                    id:0,
                    image_name: null,
                    name: null,
                    price: null,
                    time: null,
                    detail:null,
                    active: false,
                    url: null
                },
                successSound:new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3')
            }
        },
        components: {
            'editor': Editor
        },
        props: ['special_id_link'],
        mounted() {
            const self = this;

            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;

            self.special_id = self.special_id_link;
            
            if(self.special_id > 0){
                self.specialDetail();
            }
        },
        methods:{
            changeImage(e){
                const self = this;

                var files = e.target.files || e.dataTransfer.files;
                if (!files.length){
                    return;
                }
                    
                self.special.image_name = files[0];
                self.previewImageUrl =  URL.createObjectURL(self.special.image_name);
                //console.log(self.image_name);
            },
            specialDetail(){
                const self = this;

                axios.get('/json/special/detail/'+self.special_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.special = data.obj;

                    self.special_id = self.special.id;

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
                formData.append('special_id', self.special_id);
                formData.append('image_name', self.special.image_name); 

                axios.post('/admin/specials/updateimage', 
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                
                })
                .then(response => {
                    self.loading = false;

                    self.special.image_name = null;
                    self.previewImageUrl =  null;

                    self.specialDetail();
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
            updateSpecial(e){
                const self = this;

                e.preventDefault();
        
                axios.patch('/admin/specials/'+self.special_id,{
                    name: self.special.name,
                    price: self.special.price,
                    time: self.special.time,
                    active: self.special.active,
                    detail: self.special.detail,
                    url: self.special.url
                })
                .then(response => {
                    
                    var data = response.data;

                    self.special_id = data.obj.id;

                    self.specialDetail();
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
