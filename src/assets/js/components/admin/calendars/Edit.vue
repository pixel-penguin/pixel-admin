<template>
    <div>
		<div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form @submit="updateCalendar">
                    
                    
                    <div class="form-group">
                        <label>Title</label>
                        <input required class="form-control" type="text" v-model="calendar.name">
                    </div>

                    <div class="form-group">
                        <label>Author (leave blank for your own name)</label>
                        <input required class="form-control" type="text" v-model="calendar.author">
                    </div>

                    <div class="form-group">
                        <label>Categories</label>
                        <multiselect
                            v-model="calendarCategories"
                            :options="calendarCategoryOptions"
                            label="name" 
                            track-by="name"
                            multiple >
                        </multiselect>
                    </div>

                    <div class="form-group">
                        <label>Tags</label>

                        <tags-input element-id="tags"
                        v-model="calendar.tags"></tags-input>
                    </div>

                    <div class="form-group">
                        <label>Content</label>

                        <div style="height:auto" @click="openEditor" class="form-control" v-html="calendar.detail" ></div>
                    </div>

                    <div class="form-group">
                        <!--<label>Active</label>
                        <input type="checkbox" v-model="calendar.active">-->

                        <div v-if="calendar.active" @click="calendar.active = false" class="btn btn-success">Published</div>
                        <div v-else class="btn btn-danger"  @click="calendar.active = true">Not Published</div>
                    </div>
                    

                    <div class="m-t-20 text-center">
                        <a href="/admin/calendars" class="btn btn-default btn-lg">Go Back</a>
                        <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                    </div>

                    <div class="form-group" v-if="calendar.id != 0">
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
                                <li class="col-md-4 col-sm-2 col-xs-4 col-lg-2 list-group-item" v-for="item in calendarGallery" :key="item.id" >
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
                v-model="calendar.detail"
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
    
    import Multiselect from 'vue-multiselect'
    import Editor from '@tinymce/tinymce-vue';
    import SimpleModal from 'simple-modal-vue'

    export default {
        name: 'Edit-Calendar',
        data(){
            return{
                
                cloudinaryCloudName:null,

                loading: false,
                galleryLoading: false,
                previewImageUrl:null,
                calendar_id: 0,
                calendarGallery: [],
                calendar:{
                    id:0,
                    name: null,
                    detail: null,
                    active: false,
                    date_from: null,
                    date_to: null,
                    multiple_days: null,
                    whole_day: null
                },
                successSound:new Audio('https://res.cloudinary.com/dhmwdhirs/video/upload/v1558165617/audio/admin-sounds/bigbox.mp3'),

                calendarCategoryOptions:[],
                calendarCategories:[],
                tags: [],

                screenHeight: null,
                editContent: false
            }
        },
        props: ['calendar_id_link'],
        components: { 
            Multiselect,
            'editor': Editor ,
            SimpleModal 
        },
        mounted() {
            const self = this;

            self.cloudinaryCloudName = process.env.MIX_CLOUDINARY_CLOUD_NAME;

            self.calendar_id = self.calendar_id_link;
            
            if(self.calendar_id > 0){
                self.calendarDetail();
            }

            
            self.getGalleryFiles();
            self.getCalendarCategories();

        },
        methods:{
            openEditor(){
                const self = this;

                self.screenHeight = window.innerHeight;
                
                //alert(self.screenHeight);

                self.editContent = true; 
            },
            getCalendarCategories(){
                const self = this;

                axios.get('/json/calendarcategories/get')
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.calendarCategoryOptions = data.obj;

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            onOrder: function (event) {
                const self = this;

                console.log('Ordering');
                this.calendarGallery.splice(event.newIndex, 0, this.calendarGallery.splice(event.oldIndex, 1)[0])

                self.saveGalleryOrder();
            },
            calendarDetail(){
                const self = this;

                axios.get('/json/calendar/detail/'+self.calendar_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.calendar = data.obj;

                    self.calendar_id = self.calendar.id;

                    self.calendarCategories = data.obj.categories;

                })
                .catch(error => {
                    console.log(error)
                    this.errored = true
                })
            },
            updateCalendar(e){
                const self = this;

                e.preventDefault();
        
                axios.patch('/admin/calendars/'+self.calendar_id,{
                    name: self.calendar.name,
                    tags: self.calendar.tags,
                    active: self.calendar.active,
                    detail: self.calendar.detail,
                    calendar_category: self.calendarCategories,
                    author: self.calendar.author
                })
                .then(response => {
                    
                    var data = response.data;

                    self.calendar_id = data.obj.id;

                    self.calendarDetail();
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
            
            saveGalleryOrder(){
                const self = this;

                console.log(self.calendarGallery);

                axios.post('/admin/calendars/gallery/order/'+self.calendar_id,{
                    calendarGallery:self.calendarGallery,
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
                    formData.append('calendar_id', self.calendar_id);
                    formData.append('image_name', self.galleryFiles[count]); 

                    axios.post('/admin/calendars/gallery/upload', formData,formData,
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

                axios.get('/json/calendar/gallery/'+self.calendar_id)
                .then(response => {
                    //console.log(response);

                    var data = response.data;
                    
                    self.calendarGallery = data.obj;
                    
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
                            axios.delete('/admin/calendars/gallery/delete/'+galleryId).then(response => {
								
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

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style src="@voerro/vue-tagsinput/dist/style.css"></style>