<template>
    <div>
		<div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form>


                    <div v-if="previewImageUrl != null" class="form-group">
                        <img :src="previewImageUrl" width="100%" />
                    </div>
                    
                    <div v-if="previewImageUrl == null && image_name != null" class="form-group">
                        <img :src="'https://res.cloudinary.com/'+cloudinaryCloudName+'/image/upload/c_limit,h_1000,w_1000/v1/'+image_name+'.jpg'" width="100%" />
                    </div>


                    <div class="form-group">
                        <label>Image</label>
                        <input accept="image/x-png,image/gif,image/jpeg" v-on:change="changeImage" type="file" class="form-control" required>
                        <div style="margin-top:10px">
                            <div v-if="previewImageUrl != null && loading == false" @click="updatePageImage" class="btn btn-primary">Update Image</div>
                            <div v-if="previewImageUrl != null && loading" class="btn btn-primary"><i class='fa fa-circle-o-notch fa-spin'></i> Processing</div>
                        </div>

                    </div>

                    <div class="form-group">

                        <label>Name</label>
                        <input class="form-control" type="text" v-model="name">
                    
                    </div>  

                     <div class="form-group">

                        <label>Title</label>
                        <input class="form-control" type="text" v-model="title">
                    
                    </div>  

                    <div class="form-group">

                        <label>Link Url</label>
                        <input class="form-control" type="text" v-model="link_url">
                    
                    </div>  
                    
                     <div class="form-group">

                        <label>Detail</label>
                        <textarea class="form-control" v-model="detail">

                        </textarea>
                    
                    </div>  
                        
                        

                    <div class="m-t-20 text-center">
                        <a href="/admin/productcategories" class="btn btn-default btn-lg">Go Back</a>
                        <div @click="updateProductCategory" class="btn btn-primary btn-lg">Save Changes</div>
                    </div>

                    
                </form>
            </div>
        </div>

        <simple-modal v-model="editContent" title="Edit">
            <template slot="body">
                <editor 
                v-if="screenHeight != null"
                v-model="detail"
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
                    
                }">
                    <textarea></textarea>
                </editor>

               
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
        name: 'Edit-Page',
        data(){
            return{
                cloudinaryCloudName:null,

                advancedDetail: false,

                loading:false,

                name: null,
                product_category_type_id: 0,
                website_link: null,
                link_name: null,
                title: null,
                meta_description: null,
                detail: null,
                detail_summary: null,
                price:0,
                no_link:false,
                active:false,
                hidden: false,

                screenHeight: null,
                editContent: false,
            
                galleryFiles: null,

                galleryLoading: false,

                image_name: null,
                link_url: null,
                previewImageUrl: null,
                
                successSound:new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3')
            }
        },
        components: {
            'editor': Editor,
            SimpleModal         
        },
        props: ['product_category_id'],
        mounted() {
            const self = this;


            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;

            self.productCategoryDetail();            

        },
        methods:{
            changeImage(e){
                const self = this;

                var files = e.target.files || e.dataTransfer.files;
                if (!files.length){
                    return;
                }
                    
                self.image_name = files[0];
                self.previewImageUrl =  URL.createObjectURL(self.image_name);
                //console.log(self.image_name);
            },
            updatePageImage(){
                const self = this;
                
                self.loading = true;

                var formData = new FormData();
                formData.append('product_category_id', self.product_category_id);
                formData.append('image_name', self.image_name); 

                axios.post('/admin/productcategories/updateimage', 
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                
                })
                .then(response => {
                    self.loading = false;

                    self.image_name = null;
                    self.previewImageUrl =  null;

                    self.productCategoryDetail();
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
            openEditor(){
                const self = this;

                self.screenHeight = window.innerHeight;
                $('html,body').scrollTop(0);
                //alert(self.screenHeight);

                setTimeout(function(){
                    self.editContent = true; 
                }, 200);
            },
            onOrder: function (event) {
                const self = this;

                console.log('Ordering');
                this.productCategoryGallery.splice(event.newIndex, 0, this.productCategoryGallery.splice(event.oldIndex, 1)[0])

                self.saveGalleryOrder();
            },
            changeImage(e){
                const self = this;

                var files = e.target.files || e.dataTransfer.files;
                if (!files.length){
                    return;
                }
                    
                self.image_name = files[0];
                self.previewImageUrl =  URL.createObjectURL(self.image_name);
                //console.log(self.image_name);
            },
            productCategoryDetail(){
                const self = this;

                axios.get('/json/productcategories/detail/'+self.product_category_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.name = data.obj.name;
                    self.title = data.obj.title;
                    self.detail = data.obj.detail;
                    self.active = data.obj.active;
                    self.image_name = data.obj.image_name;
                    self.link_url = data.obj.link_url;

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            updateProductCategory(){
                const self = this;

        
                axios.patch('/admin/productcategories/'+self.product_category_id,{
                    name: self.name,
                    link_name: self.link_name,
                    title: self.title,
                    detail: self.detail,
                    active: self.active,
                    link_url: self.link_url
                })
                .then(response => {
                    self.productCategoryDetail();
                    this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Updated Product Category Successfully'
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
