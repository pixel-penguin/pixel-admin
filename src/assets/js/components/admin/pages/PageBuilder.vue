<template>
    <div>
		<div class="row">
            <div class="col-md-8 col-md-offset-2">

                <ul class="list-group" v-sortable="{ handle: '.handle', onUpdate: onUpdate }">
                    
                    
                    <li v-for="pageContent in pageContents"  v-bind:key="pageContent.id" class="list-group-item">
                        
                        <div class="row" v-if="pageContent.page_content_type_id == 1">
                            
                            <div class="col-xs-10 col-sm-10 col-md-10">     
                                <editor v-if="pageContent.edit"
                                            v-model="pageContent.detail"
                                            api-key="9tv5nzovtpredmv4b778na7pauhqz7n3rk4pviia6wla45v2" 
                                            :init="{
                                                browser_spellcheck: true,
                                                plugins: 'wordcount, image, media, link, table, lists, code, autoresize', 
                                                //menubar: 'insert',
                                                toolbar: 'image, media, link, table, numlist bullist, code',
                                                file_picker_types: 'file image media',
                                                //extended_valid_elements: 'svg[*],defs[*],pattern[*],desc[*],metadata[*],g[*],mask[*],path[*],line[*],marker[*],rect[*],circle[*],ellipse[*],polygon[*],polyline[*],linearGradient[*],radialGradient[*],stop[*],image[*],view[*],text[*],textPath[*],title[*],tspan[*],glyph[*],symbol[*],switch[*],use[*]',
                                                file_browser_callback_types: 'file image media',
                                                automatic_uploads: true,
                                                images_upload_url: '/admin/mcefileupload',
                                                
                                                link_assume_external_targets: true,               
                                            }"></editor>
                                <div style="margin-top:10px" @click="pageContent.edit = false; saveContent(pageContent.id, pageContent.detail)" v-if="pageContent.edit" class="btn btn-primary">Save</div>                        
                                <div @click="pageContent.edit = true" v-if="pageContent.edit == false" v-html="pageContent.detail"></div>
                                <small v-if="pageContent.edit" style="float:right; margin-top:15px">If you wish to upload a file and attach it to the page <a target="_blank" href="/admin/cloudfiles">click here</a> to upload the file and link it here</small>
                            </div>

                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <i class="fa fa-arrows handle" aria-hidden="true"></i>
                                <i @click="destroyPageContent(pageContent.id)" v-tooltip="'Delete Content Section'" class="fa fa-trash" aria-hidden="true"  style="color: #C51515"></i>
                            </div>
                        </div>

                        <div class="row" v-if="pageContent.page_content_type_id == 5">
                            
                            <div class="col-xs-10 col-sm-10 col-md-10">     
                                
                                <div class="row">
                                    <div v-for="galleryEntery in pageContent.gallery" v-bind:key="galleryEntery.id" class="col-xs-3 col-sm-3 col-md-3" style="text-align:center"> 
                                        <div style="background:#f7f7f7; padding-bottom:20px; margin-bottom:10px; border-radius:10px">
                                            <span @click="destroyGalleryFile(galleryEntery.id)" class="product-remove" title="remove" style="right:20px; top:0"><i class="fa fa-close"></i></span>
                                            <img style="max-width:90%; margin-top:20px; border-radius:10px;" :src="'https://res.cloudinary.com/'+cloudinaryCloudName+'/image/upload/c_fill,h_240,w_360/v1/'+galleryEntery.image_name+'.jpg'" />
                                        </div>
                                    </div>   
                                    <div class="col-xs-12 col-sm-12 col-md-12"> 
                                        <input class="form-control" type="file" v-on:change="onGalleryChange" multiple>
                                        <small class="help-block">Max. file size: 10 MB. Allowed images: jpg, gif, png.</small>
                                        <div v-if="galleryLoading == false" @click="uploadGallery(0, pageContent.id)" class="btn btn-primary btn-sm">Upload Images</div>
                                        <div v-if="galleryLoading == true" class="btn btn-primary btn-sm"><i class='fa fa-circle-o-notch fa-spin'></i> Uploading</div>    
                                    </div>    
                                </div>
                            </div>

                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <i class="fa fa-arrows handle" aria-hidden="true"></i>
                                <i @click="destroyPageContent(pageContent.id)" v-tooltip="'Delete Content Section'" class="fa fa-trash" aria-hidden="true"  style="color: #C51515"></i>
                            </div>
                        </div>
                        
                    </li>
                    
                </ul>

                <a :href="'/admin/pages/'+page_id+'/edit/'" class="btn btn-primary">Go Back</a>
                <div @click="addSection = true" v-if="addSection == false" class="btn btn-primary">Add new section</div>
                <div @click="addSection = false" v-if="addSection" class="btn btn-primary">Cancel</div>
                
                <div v-if="addSection" class="row" id="pageTypeSelection">
                    
                    
                    <div v-for="pageContentType in pageContentTypes" v-bind:key="pageContentType.id" class="col-md-4 pageChoice" @click="addContentSection(pageContentType.id)">
                        <div>
                            <div><img :src="'https://res.cloudinary.com/dhmwdhirs/image/upload/c_limit,h_100/v1/'+pageContentType.icon_name+'.png'" /></div>
                            <p>{{pageContentType.name}}</p>
                        </div>
                    </div>
                    
                </div>

                
            </div>
        </div>

    </div>
</template>

<script>

    import Editor from '@tinymce/tinymce-vue';

    export default {
        name: 'Edit-Page',
        data(){
            return{
                cloudinaryCloudName:null,
                
                addSection:false,

                pageContents:[],
                pageContentTypes: [],

                galleryFiles:[],

                content:[],

                galleryLoading: false
            }
        },
        components: {
            'editor': Editor            
        },
        props: ['page_id'],
        mounted() {
            const self = this;

            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;

            self.getContentTypes();
            self.getContents();

            //self.getGalleryFiles();

        },
        methods:{

            saveContent(pageContentId, detail){
                const self = this;

                axios.post('/admin/pages/builder/updateinfo',{
                    page_content_id: pageContentId,
                    detail:detail
                })
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.getContents();
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            onUpdate: function (event) {
                //this.list.splice(event.newIndex, 0, this.list.splice(event.oldIndex, 1)[0])
                const self = this;
                self.pageContents.splice(event.newIndex, 0, self.pageContents.splice(event.oldIndex, 1)[0])
                //console.log(self.pageContents);

                axios.post('/admin/pages/builder/order',{
                    content:self.pageContents,
                    //content: 
                })
                .then(response => {
                    /*
                    if (!response.ok) {
                    throw new Error(response.statusText)
                    }
                    return response.json()
                    */
                })
                .catch(error => {
                    self.$swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            },
            getContentTypes(){
                const self = this;

                axios.get('/admin/json/getpagecontenttypes')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.pageContentTypes = data.obj;
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            
            getContents(){
                const self = this;

                axios.get('/admin/json/getpagecontents/'+self.page_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.pageContents = data.obj;
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            addContentSection(contentTypeId){
                const self = this;

                axios.post('/admin/json/addcontentsection',{
                    page_id:self.page_id,
                    content_type_id:contentTypeId
                })
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.getContents();
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            destroyPageContent(pagecContentId){
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
                            axios.delete('/admin/json/removecontentsection/'+pagecContentId).then(response => {
								//self.getAllPages();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Content Section Deleted successfully'
								})

                                self.getContents();
								//self.showAddForm = false;
							})
							.catch(e => {
								this.errors.push(e)
							})
                        }
                    })
            },
            uploadGallery(count = 0, pageContentId){
                const self = this;
                
                //console.log(count);
                //console.log(self.galleryFiles.length);

                self.galleryLoading = true;

                if(count < self.galleryFiles.length){
                    
                    //console.log(self.galleryFiles)
                    //console.log(self.galleryFiles[count])

                    var formData = new FormData();
                    formData.append('page_content_id', pageContentId);
                    formData.append('image_name', self.galleryFiles[count]); 

                    axios.post('/admin/pages/builder/uploadgallery', formData,formData,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    
                    })
                    .then(response => {
                        count++;
                        self.uploadGallery(count, pageContentId);
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

                    self.getContents();
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

                //alert(galleryId);
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
                            axios.delete('/admin/pages/builder/gallery/delete/'+galleryId).then(response => {
								
                                self.getContents();

								this.$swal({
									toast: true,
									position: 'bottom-end',
									showConfirmButton: false,
									timer: 3000,
									type: 'success',
									title: 'Image Deleted successfully'
								})

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
