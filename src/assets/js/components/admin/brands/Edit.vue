<template>
    <div>
         <div class="row" style="margin-bottom:10px">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4" style="padding-left:0">
                        <a href="/admin/brands" class="btn btn-white"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
                    </div>
                </div>
            </div>
        </div>
		<div class="row">
            <div class="col-md-12 card" style="padding:10px">
                <form @submit="updateBrand">
                    
                    
                    <div v-if="brand_id > 0">
                        <div v-if="previewImageUrl != null" class="form-group">
                            <img :src="previewImageUrl" width="100%" />
                        </div>
                        
                        <div v-if="previewImageUrl == null && brand.image_name != null" class="form-group" style="text-align:center">
                            <img style="max-width:300px" :src="'https://res.cloudinary.com/'+cloudinaryCloudName+'/image/upload/c_limit,h_1000,w_1000/v1/'+brand.image_name+'.jpg'" width="100%" />
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input accept="image/x-png,image/gif,image/jpeg" v-on:change="changeImage" type="file" class="form-control">
                            <div style="margin-top:10px">
                                <div v-if="previewImageUrl != null && loading == false" @click="updateImage" class="btn btn-primary">Update Image</div>
                                <div v-if="previewImageUrl != null && loading" class="btn btn-primary"><i class='fa fa-circle-o-notch fa-spin'></i> Processing</div>
                            </div>

                        </div>
                    </div>

                    
                    
                    <div class="form-group">
                        <label>Name</label>
                        <input required class="form-control" type="text" v-model="brand.name">
                    </div>

                    <div class="form-group">
                        <label>Content</label>

                        <div style="height:auto" @click="openEditor" class="form-control" v-html="brand.detail" ></div>
                    </div>

                    <div class="form-group">
                        <label>Active</label>
                        <input type="checkbox" v-model="brand.active">
                    </div>
                    

                    <div class="m-t-20 text-center">
                        <a href="/admin/brands" class="btn btn-default btn-lg">Go Back</a>
                        <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                    </div>

                    
                </form>
            </div>
        </div>
        <simple-modal v-model="editContent" title="Edit">
            <template slot="body">
                <editor 
                v-if="screenHeight != null"
                v-model="brand.detail"
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
                    height: screenHeight - 200,        
                    
                    link_assume_external_targets: true,                               
                }"></editor>

                <div style="margin-top:10px; text-align:right">
                    <div @click="editContent = false" class="btn btn-primary">Apply</div>
                </div>
            </template>
            <template slot="footer">
                <button>OK</button>
            </template>
        </simple-modal>
    </div>
</template>

<script>

    import Editor from '@tinymce/tinymce-vue';
    import SimpleModal from 'simple-modal-vue'
    export default {
        name: 'Edit-Brand',
        data(){
            return{
                cloudinaryCloudName:null,

                loading: false,
                previewImageUrl:null,
                brand_id: 0,
                brand:{
                    id:0,
                    image_name: null,
                    name: null,
                    detail:null,
                    active: false,
                },
                successSound:new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3'),
                
                screenHeight: null,
                editContent: false
            }
        },
        props: ['brand_id_link'],
        components: { 
            'editor': Editor ,
            SimpleModal 
        },
        mounted() {
            const self = this;

            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;

            self.brand_id = self.brand_id_link;
            
            if(self.brand_id > 0){
                self.brandDetail();
            }
        },
        methods:{
            openEditor(){
                const self = this;

                self.screenHeight = window.innerHeight;
                $('html,body').scrollTop(0);
                //alert(self.screenHeight);

                setTimeout(function(){
                    self.editContent = true; 
                }, 200);
            },
            changeImage(e){
                const self = this;

                var files = e.target.files || e.dataTransfer.files;
                if (!files.length){
                    return;
                }
                    
                self.brand.image_name = files[0];
                self.previewImageUrl =  URL.createObjectURL(self.brand.image_name);
                //console.log(self.image_name);
            },
            brandDetail(){
                const self = this;

                axios.get('/json/brand/detail/'+self.brand_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.brand = data.obj;

                    self.brand_id = self.brand.id;

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
                formData.append('brand_id', self.brand_id);
                formData.append('image_name', self.brand.image_name); 

                axios.post('/admin/brands/updateimage', 
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                
                })
                .then(response => {
                    self.loading = false;

                    self.brand.image_name = null;
                    self.previewImageUrl =  null;

                    self.brandDetail();
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
            updateBrand(e){
                const self = this;

                e.preventDefault();
        
                axios.patch('/admin/brands/'+self.brand_id,{
                    name: self.brand.name,
                    active: self.brand.active,
                    detail: self.brand.detail
                })
                .then(response => {
                    
                    var data = response.data;

                    self.brand_id = data.obj.id;

                    self.brandDetail();
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
