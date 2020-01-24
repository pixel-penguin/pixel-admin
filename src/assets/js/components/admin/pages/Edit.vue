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
                        <label>Page Type</label>
                        <select v-model="page_type_id" class="form-control">
                            <option v-for="pageType in pageTypesList" :key="pageType.id" :value="pageType.id">{{pageType.name}}</option>
                        </select>
                    </div>

                    <div class="form-group" v-if="page_type_id == 1 || page_type_id == 4">
                        <label>Content</label>
                        <div @click="openEditor" style="width:100%;; background:#FFF; border:#CCC 1px solid; padding:10px; margin-bottom:20px; cursor:pointer" v-html="detail"></div>
                        <div v-if="page_type_id == 1 || page_type_id == 4" @click="openEditor" class="btn btn-default">Edit Page Content</div>
                    </div>
                    
                    
                    <div class="form-group" style="margin-bottom:20px">
                        <a v-if="page_type_id == 3 || page_type_id == 5" :href="'/admin/pages/builder/'+page_id" class="btn btn-primary">Page Builder</a>
                    </div>
                    
                    
                    <div style="margin-bottom:20px" v-if="advancedDetail == false" @click="advancedDetail = true" class="btn btn-primary">Show Advanced Detail</div>
                    <div style="margin-bottom:20px" v-if="advancedDetail" @click="advancedDetail = false" class="btn btn-primary">Hide Advanced Detail</div>
                    
                    <div v-if="advancedDetail">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input class="form-control" type="text" v-model="title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Link Name</label>
                                    <input class="form-control" type="text" v-model="link_name">
                                </div>
                            </div>
                        </div>
                        
                        
                        <!--<div class="form-group">
                            <label>Tags <small>(separated with a comma)</small></label>
                            <input type="text" placeholder="Enter your tags" data-role="tagsinput" class="form-control">
                        </div>
                        -->
                    
                        <div class="row">
                            
                            
                            <div  v-if="page_type_id != 5"  class="col-md-6">
                                <div class="form-group">
                                    <label>Page link (if not standard)</label>
                                    <input class="form-control" type="text" v-model="website_link">
                                </div>
                            </div>

                            <div v-if="page_type_id != 5" class="col-md-6">
                                <div class="form-group">
                                    <label>Meta Description</label>
                                    <input class="form-control" type="text" v-model="meta_description">
                                </div>
                            </div>

                            <div v-if="page_type_id == 5" class="col-md-12">
                                <div class="form-group">
                                    <label>Small Description</label>
                                    <textarea class="form-control" v-model="detail_summary"></textarea>
                                </div>
                            </div>

                            <div v-if="page_type_id == 5" class="col-md-6">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input class="form-control" type="number" v-model="price">
                                </div>
                            </div>

                            

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Other</label><br>
                                    <p-check class="p-switch p-fill" v-model="active">Published</p-check>
                                    <p-check class="p-switch p-fill" v-model="hidden">Link Hidden</p-check>
                                    <p-check class="p-switch p-fill" v-model="no_link">Link Not Clickable</p-check>
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    <div class="m-t-20 text-center">
                        <a href="/admin/pages" class="btn btn-default btn-lg">Go Back</a>
                        <div @click="updatePage" class="btn btn-primary btn-lg">Save Changes</div>
                    </div>

                    <div class="form-group" v-if="page_type_id == 1 || page_type_id == 2">
                        <label>Gallery</label>
                        <div>
                            <input class="form-control" type="file" v-on:change="onGalleryChange" multiple>
                            <small class="help-block">Max. file size: 10 MB. Allowed images: jpg, gif, png.</small>
                            <div style="margin-bottom:20px">
                                <div v-if="galleryLoading == false" @click="uploadGallery(0)" class="btn btn-primary btn-sm">Upload Images</div>
                                <!--<div v-if="galleryLoading == false" @click="saveGalleryOrder" class="btn btn-primary btn-sm">Save Gallery Order</div>-->
                                <div v-if="galleryLoading == true" class="btn btn-primary btn-sm"><i class='fa fa-circle-o-notch fa-spin'></i> Uploading</div>                            
                            </div>
                        </div>
                        <div class="row">
                            
                            <ul class="list-group"  v-sortable="{ onUpdate: onOrder }">
                                <li class="col-md-4 col-sm-2 col-xs-4 col-lg-2 list-group-item" v-for="item in pageGallery" :key="item.id" >
                                    <div class="thumbnail product-thumbnail">
                                        <div class="thumb">
                                            <img :src="'https://res.cloudinary.com/'+cloudinaryCloudName+'/image/upload/c_fill,h_100,w_150/v1548624788/'+item.image_name+'.jpg'" class="img-responsive" alt="">
                                        </div>
                                        <span @click="destroyGalleryFile(item.id)" class="product-remove" title="remove"><i class="fa fa-close"></i></span>
                                    </div>
                                </li>
                            </ul>
                            
                        </div>
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
        name: 'Edit-Page',
        data(){
            return{
                cloudinaryCloudName:null,

                advancedDetail: false,

                loading:false,

                name: null,
                page_type_id: 0,
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

                pageGallery: [],

                screenHeight: null,
                editContent: false,
            
                galleryFiles: null,

                galleryLoading: false,

                pageTypesList:[],

                image_name: null,
                previewImageUrl: null,
                
                successSound:new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3')
            }
        },
        components: {
            'editor': Editor,
            SimpleModal         
        },
        props: ['page_id'],
        mounted() {
            const self = this;


            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;

            self.pageDetail();
            self.pageTypes();

            self.getGalleryFiles();

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
            onOrder: function (event) {
                const self = this;

                console.log('Ordering');
                this.pageGallery.splice(event.newIndex, 0, this.pageGallery.splice(event.oldIndex, 1)[0])

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
            pageDetail(){
                const self = this;

                axios.get('/json/page/detail/'+self.page_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.name = data.obj.name;
                    self.website_link = data.obj.website_link;
                    self.link_name = data.obj.link_name;
                    self.title = data.obj.title;
                    self.meta_description = data.obj.meta_description;
                    self.detail = data.obj.detail;
                    self.detail_summary = data.obj.detail_summary;
                    self.price = data.obj.price;
                    self.no_link = data.obj.no_link;
                    self.active = data.obj.active;
                    self.hidden = data.obj.hidden;
                    self.page_type_id = data.obj.page_type_id;
                    self.image_name = data.obj.image_name;

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            pageTypes(){
                const self = this;

                axios.get('/admin/json/getpagetypes')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.pageTypesList = data.obj;
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            updatePageImage(){
                const self = this;
                
                self.loading = true;

                var formData = new FormData();
                formData.append('page_id', self.page_id);
                formData.append('image_name', self.image_name); 

                axios.post('/admin/pages/updatepageimage', 
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

                    self.pageDetail();
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
            updatePage(){
                const self = this;

        
                axios.patch('/admin/pages/'+self.page_id,{
                    name: self.name,
                    website_link: self.website_link,
                    page_type_id: self.page_type_id,
                    link_name: self.link_name,
                    title: self.title,
                    meta_description: self.meta_description,
                    detail: self.detail,
                    detail_summary: self.detail_summary,
                    price: self.price,
                    no_link: self.no_link,
                    active: self.active,
                    hidden: self.hidden,
                })
                .then(response => {
                    self.pageDetail();
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
            saveGalleryOrder(){
                const self = this;

                console.log(self.pageGallery);

                axios.post('/admin/pages/gallery/order/'+self.page_id,{
                    pageGallery:self.pageGallery,
                })
                .then(response => {
                    /*
                    if (!response.ok) {
                    throw new Error(response.statusText)
                    }
                    return response.json()
                    */
                   this.$swal({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        type: 'success',
                        title: 'Ordered Successfully'
                    });
                    
                    self.successSound.play();

                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            },
            uploadGallery(count = 0){
                const self = this;
                
                //console.log(count);
                //console.log(self.galleryFiles.length);

                self.galleryLoading = true;

                if(count < self.galleryFiles.length){
                    
                    //console.log(self.galleryFiles)
                    //console.log(self.galleryFiles[count])

                    var formData = new FormData();
                    formData.append('page_id', self.page_id);
                    formData.append('image_name', self.galleryFiles[count]); 

                    axios.post('/admin/pages/uploadgallery', formData,formData,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    
                    })
                    .then(response => {
                        count++;
                        self.uploadGallery(count);
                    })
                    .catch(error => {
                        self.$swal.showValidationMessage(
                            `Request failed: ${error}`
                        )

                        alert('Something went wrong with uploads');
                        
                        self.galleryLoading = false;
                    })
                }
                else{
                    //console.log(count);
                    //console.log('done');

                    self.galleryLoading = false;

                    self.getGalleryFiles();
                }
                
                //console.log(self.galleryFiles);
            },
            onGalleryChange(e) {
                const self = this;
                
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                self.galleryFiles = files;
            },
            getGalleryFiles(){
                const self = this;

                axios.get('/json/page/gallery/'+self.page_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.pageGallery = data.obj;
                    
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            },
            destroyGalleryFile(galleryId){
				const self = this;

				//console.log(rowId);
				
				this.$swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            axios.delete('/admin/pages/gallery/delete/'+galleryId).then(response => {
								
                                self.getGalleryFiles();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Image Deleted successfully'
								})
                                self.successSound.play();

								//self.showAddForm = false;
							})
							.catch(e => {
								this.errors.push(e)
							})
                        }
                    })
			},
            
        }

    }

</script>
